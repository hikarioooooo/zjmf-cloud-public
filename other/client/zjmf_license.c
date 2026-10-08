#include "php.h"
#include "php_ini.h"
#include "ext/standard/info.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <ctype.h>
#include <time.h>
#include <unistd.h>

/* Target: PHP 7.2 NTS. Only the observed authorization key and endpoints match. */
static char *replacement_key=NULL, *target_key=NULL, *legacy_key=NULL;
static HashTable key_fallbacks, original_urls;
static int request_ready=0;
PHP_INI_BEGIN()
 PHP_INI_ENTRY("zjmf_license.legacy_public_key","",PHP_INI_SYSTEM,NULL)
 PHP_INI_ENTRY("idcsmart.url","",PHP_INI_SYSTEM,NULL)
 PHP_INI_ENTRY("zjmf_license.url","http://127.0.0.1:18731/",PHP_INI_SYSTEM,NULL)
 PHP_INI_ENTRY("zjmf_license.public_key","/opt/zjmf-license/keys/public.pem",PHP_INI_SYSTEM,NULL)
 PHP_INI_ENTRY("zjmf_license.target_key","/opt/zjmf-license/keys/target.pem",PHP_INI_SYSTEM,NULL)
 PHP_INI_ENTRY("zjmf_license.log","/var/log/zjmf-lab/license.log",PHP_INI_SYSTEM,NULL)
 PHP_INI_ENTRY("zjmf_license.integrity","1",PHP_INI_SYSTEM,NULL)
PHP_INI_END()
static void log_event(const char *what,const char *detail){
 FILE *f=fopen(INI_STR("zjmf_license.log"),"a");if(!f)return;
 char safe[301];size_t len=detail?strcspn(detail,"?"):0;if(len>300)len=300;
 if(len){memcpy(safe,detail,len);}
 safe[len]='\0';
 fprintf(f,"%ld pid=%d %s %s\n",(long)time(NULL),(int)getpid(),what,safe);fclose(f);
}
static char *read_native(const char *path){
 FILE *f=fopen(path,"rb");if(!f)return NULL;
 if(fseek(f,0,SEEK_END)){fclose(f);return NULL;}long len=ftell(f);
 if(len<1||len>16384){fclose(f);return NULL;}rewind(f);
 char *s=malloc((size_t)len+1);if(!s){fclose(f);return NULL;}
 if(fread(s,1,(size_t)len,f)!=(size_t)len){free(s);fclose(f);return NULL;}
 s[len]='\0';fclose(f);return s;
}
static char *normalize(const char *s,size_t len){
 char *out=emalloc(len+1);size_t i,n=0;
 for(i=0;i<len;i++){if(!isspace((unsigned char)s[i]))out[n++]=s[i];}
 out[n]='\0';return out;
}
static zval *argument(zend_execute_data *ex,unsigned n){
 if(ZEND_CALL_NUM_ARGS(ex)<n){return NULL;}
 zval *v=ZEND_CALL_ARG(ex,n);ZVAL_DEREF(v);return v;
}
static char *mapped_url(const char *url,size_t len){
 const char *origins[]={"https://license.soft13.idcsmart.com/","https://license7.idcsmart.com/","https://my.idcsmart.com/"};
 size_t i;const char *dest=INI_STR("idcsmart.url");
 if(!dest||!*dest)dest=INI_STR("zjmf_license.url");
 if(!dest||!*dest)return NULL;
 for(i=0;i<sizeof(origins)/sizeof(origins[0]);i++){
  size_t n=strlen(origins[i]);
  if(len>=n&&!memcmp(url,origins[i],n)){
   const char *tail=url+n;
   /* Only authorization, entitlement and version endpoints; no asset downloads. */
   if(i==2 && strncmp(tail,"market/index",12))return NULL;
   char *mapped=emalloc(strlen(dest)+len-n+2);
   strcpy(mapped,dest);if(mapped[strlen(mapped)-1]!='/')strcat(mapped,"/");strcat(mapped,tail);return mapped;
  }
 }
 return NULL;
}
static void remember_url(zval *handle,zval *url){
 if(request_ready && handle && Z_TYPE_P(handle)==IS_RESOURCE){zval copy;ZVAL_COPY(&copy,url);zend_hash_index_update(&original_urls,Z_RES_HANDLE_P(handle),&copy);}
}
#define ORIG(n) static void (*original_##n)(INTERNAL_FUNCTION_PARAMETERS)=NULL
ORIG(curl_setopt);ORIG(curl_setopt_array);ORIG(curl_getinfo);
ORIG(openssl_pkey_get_public);ORIG(openssl_public_decrypt);
ORIG(md5_file);ORIG(filesize);ORIG(file_exists);ORIG(file_get_contents);

PHP_FUNCTION(local_curl_setopt){
 zval *opt=argument(execute_data,2),*url=argument(execute_data,3),*handle=argument(execute_data,1);
 if(opt && Z_TYPE_P(opt)==IS_LONG && Z_LVAL_P(opt)==10002 && url && Z_TYPE_P(url)==IS_STRING){
  char *mapped=mapped_url(Z_STRVAL_P(url),Z_STRLEN_P(url));
  if(mapped){
   remember_url(handle,url);log_event("redirect",Z_STRVAL_P(url));
   zval backup;ZVAL_COPY(&backup,url);zval_ptr_dtor(url);ZVAL_STRING(url,mapped);efree(mapped);
   original_curl_setopt(INTERNAL_FUNCTION_PARAM_PASSTHRU);zval_ptr_dtor(url);ZVAL_COPY_VALUE(url,&backup);return;
  }
 }
 original_curl_setopt(INTERNAL_FUNCTION_PARAM_PASSTHRU);
}
PHP_FUNCTION(local_curl_setopt_array){
 zval *options=argument(execute_data,2),*handle=argument(execute_data,1);
 if(options && Z_TYPE_P(options)==IS_ARRAY){
  zval *url=zend_hash_index_find(Z_ARRVAL_P(options),10002);
  if(url && Z_TYPE_P(url)==IS_STRING){
   char *mapped=mapped_url(Z_STRVAL_P(url),Z_STRLEN_P(url));
   if(mapped){
    remember_url(handle,url);log_event("redirect",Z_STRVAL_P(url));
    zval backup;ZVAL_COPY(&backup,options);ZVAL_ARR(options,zend_array_dup(Z_ARRVAL(backup)));
    zval changed;ZVAL_STRING(&changed,mapped);efree(mapped);zend_hash_index_update(Z_ARRVAL_P(options),10002,&changed);
    original_curl_setopt_array(INTERNAL_FUNCTION_PARAM_PASSTHRU);zval_ptr_dtor(options);ZVAL_COPY_VALUE(options,&backup);return;
   }
  }
 }
 original_curl_setopt_array(INTERNAL_FUNCTION_PARAM_PASSTHRU);
}
PHP_FUNCTION(local_curl_getinfo){
 original_curl_getinfo(INTERNAL_FUNCTION_PARAM_PASSTHRU);
 zval *h=argument(execute_data,1);if(!request_ready||!h||Z_TYPE_P(h)!=IS_RESOURCE)return;
 zval *url=zend_hash_index_find(&original_urls,Z_RES_HANDLE_P(h));if(!url)return;
 if(Z_TYPE_P(return_value)==IS_ARRAY){
  zval copy;ZVAL_COPY(&copy,url);SEPARATE_ARRAY(return_value);zend_hash_str_update(Z_ARRVAL_P(return_value),"url",3,&copy);
 }else{
  zval *opt=argument(execute_data,2);
  if(opt&&Z_TYPE_P(opt)==IS_LONG&&Z_LVAL_P(opt)==1048577&&Z_TYPE_P(return_value)==IS_STRING){zval_ptr_dtor(return_value);ZVAL_COPY(return_value,url);}
 }
}
PHP_FUNCTION(local_openssl_pkey_get_public){
 zval *v=argument(execute_data,1);int matching=0;
 if(v&&Z_TYPE_P(v)==IS_STRING&&target_key){char *key=normalize(Z_STRVAL_P(v),Z_STRLEN_P(v));matching=!strcmp(key,target_key);efree(key);}
 original_openssl_pkey_get_public(INTERNAL_FUNCTION_PARAM_PASSTHRU);
 if(!matching||!request_ready||Z_TYPE_P(return_value)!=IS_RESOURCE)return;
 zval backup,alternatives;ZVAL_COPY(&backup,v);array_init(&alternatives);
 const char *keys[]={replacement_key,legacy_key};size_t i;
 for(i=0;i<2;i++){
  if(!keys[i])continue;
  zval new_key;zval_ptr_dtor(v);ZVAL_STRING(v,keys[i]);ZVAL_UNDEF(&new_key);
  original_openssl_pkey_get_public(execute_data,&new_key);
  if(Z_TYPE(new_key)==IS_RESOURCE)add_next_index_zval(&alternatives,&new_key);
  else if(!Z_ISUNDEF(new_key))zval_ptr_dtor(&new_key);
 }
 zval_ptr_dtor(v);ZVAL_COPY_VALUE(v,&backup);
 if(zend_hash_num_elements(Z_ARRVAL(alternatives)))zend_hash_index_update(&key_fallbacks,Z_RES_HANDLE_P(return_value),&alternatives);
 else zval_ptr_dtor(&alternatives);
}
PHP_FUNCTION(local_openssl_public_decrypt){
 zval *key=argument(execute_data,3),*alternatives=NULL;
 if(request_ready&&key&&Z_TYPE_P(key)==IS_RESOURCE)alternatives=zend_hash_index_find(&key_fallbacks,Z_RES_HANDLE_P(key));
 int reporting=EG(error_reporting);if(alternatives)EG(error_reporting)=0;
 original_openssl_public_decrypt(INTERNAL_FUNCTION_PARAM_PASSTHRU);
 if(alternatives&&Z_TYPE_P(return_value)==IS_FALSE){
  zval *alternative;
  ZEND_HASH_FOREACH_VAL(Z_ARRVAL_P(alternatives),alternative){
   zval backup;ZVAL_COPY(&backup,key);zval_ptr_dtor(key);ZVAL_COPY(key,alternative);
   original_openssl_public_decrypt(INTERNAL_FUNCTION_PARAM_PASSTHRU);zval_ptr_dtor(key);ZVAL_COPY_VALUE(key,&backup);
   if(Z_TYPE_P(return_value)==IS_TRUE)break;
  }ZEND_HASH_FOREACH_END();
 }
 EG(error_reporting)=reporting;
}
static int suffix(zval *v,const char *ending){size_t n=strlen(ending);return v&&Z_TYPE_P(v)==IS_STRING&&Z_STRLEN_P(v)>=n&&!memcmp(Z_STRVAL_P(v)+Z_STRLEN_P(v)-n,ending,n);}
PHP_FUNCTION(local_md5_file){
 original_md5_file(INTERNAL_FUNCTION_PARAM_PASSTHRU);if(!INI_BOOL("zjmf_license.integrity"))return;
 zval *path=argument(execute_data,1),*raw=argument(execute_data,2);const char *hex=NULL;
 if(suffix(path,"/extend/other/extension"))hex="277e61f87c5b280ed2a036cd3bdefcad";
 else if(suffix(path,"/extend/other/check_main"))hex="bc8cfd735124c54215c5afeeb6dac644";
 if(hex){
  zval_ptr_dtor(return_value);
  if(raw&&zend_is_true(raw)){char bytes[16];size_t i;for(i=0;i<16;i++){unsigned v;sscanf(hex+2*i,"%2x",&v);bytes[i]=(char)v;}ZVAL_STRINGL(return_value,bytes,16);}
  else ZVAL_STRING(return_value,hex);
 }
}
PHP_FUNCTION(local_filesize){
 original_filesize(INTERNAL_FUNCTION_PARAM_PASSTHRU);
 if(INI_BOOL("zjmf_license.integrity")&&suffix(argument(execute_data,1),"/extend/other/extension")){zval_ptr_dtor(return_value);ZVAL_LONG(return_value,1286144);}
}
PHP_FUNCTION(local_file_exists){
 original_file_exists(INTERNAL_FUNCTION_PARAM_PASSTHRU);
 if(INI_BOOL("zjmf_license.integrity")&&suffix(argument(execute_data,1),"/etc/php.d/40-idcsmart.ini")){zval_ptr_dtor(return_value);ZVAL_FALSE(return_value);}
}
PHP_FUNCTION(local_file_get_contents){
 zval *v=argument(execute_data,1);
 const char *blocked="https://license.soft13.idcsmart.com/app/api/sync_authorize";
 if(v&&Z_TYPE_P(v)==IS_STRING&&Z_STRLEN_P(v)>=strlen(blocked)&&!strncmp(Z_STRVAL_P(v),blocked,strlen(blocked))){ZVAL_EMPTY_STRING(return_value);return;}
 original_file_get_contents(INTERNAL_FUNCTION_PARAM_PASSTHRU);
}
typedef struct{const char *name;void(*handler)(INTERNAL_FUNCTION_PARAMETERS);void(**original)(INTERNAL_FUNCTION_PARAMETERS);} hook;
#define H(n) {#n,zif_local_##n,&original_##n}
static hook hooks[]={H(curl_setopt),H(curl_setopt_array),H(curl_getinfo),H(openssl_pkey_get_public),H(openssl_public_decrypt),H(md5_file),H(filesize),H(file_exists),H(file_get_contents)};
PHP_MINIT_FUNCTION(zjmf_license){
 REGISTER_INI_ENTRIES();
 const char *legacy_path=INI_STR("zjmf_license.legacy_public_key");if(legacy_path&&*legacy_path)legacy_key=read_native(legacy_path);
 replacement_key=read_native(INI_STR("zjmf_license.public_key"));char *raw=read_native(INI_STR("zjmf_license.target_key"));
 if(!replacement_key||!raw||(legacy_path&&*legacy_path&&!legacy_key)){free(replacement_key);replacement_key=NULL;free(legacy_key);legacy_key=NULL;free(raw);php_error_docref(NULL,E_WARNING,"Public key files could not be read");UNREGISTER_INI_ENTRIES();return FAILURE;}
 char *normalized=normalize(raw,strlen(raw));target_key=strdup(normalized);efree(normalized);free(raw);
 size_t i;for(i=0;i<sizeof(hooks)/sizeof(hooks[0]);i++){
  zend_function *f=zend_hash_str_find_ptr(CG(function_table),hooks[i].name,strlen(hooks[i].name));
  if(f&&f->type==ZEND_INTERNAL_FUNCTION){*hooks[i].original=f->internal_function.handler;}
  else{php_error_docref(NULL,E_WARNING,"Required function %s unavailable",hooks[i].name);free(replacement_key);free(target_key);free(legacy_key);replacement_key=target_key=legacy_key=NULL;UNREGISTER_INI_ENTRIES();return FAILURE;}
 }
 for(i=0;i<sizeof(hooks)/sizeof(hooks[0]);i++){
  zend_function *f=zend_hash_str_find_ptr(CG(function_table),hooks[i].name,strlen(hooks[i].name));f->internal_function.handler=hooks[i].handler;
 }
 return SUCCESS;
}
PHP_MSHUTDOWN_FUNCTION(zjmf_license){
 if(CG(function_table)){
  size_t i;for(i=0;i<sizeof(hooks)/sizeof(hooks[0]);i++){
   zend_function *f=zend_hash_str_find_ptr(CG(function_table),hooks[i].name,strlen(hooks[i].name));
   if(f&&f->type==ZEND_INTERNAL_FUNCTION&&f->internal_function.handler==hooks[i].handler){f->internal_function.handler=*hooks[i].original;}
  }
 }
 free(replacement_key);free(target_key);free(legacy_key);replacement_key=target_key=legacy_key=NULL;UNREGISTER_INI_ENTRIES();return SUCCESS;
}
PHP_RINIT_FUNCTION(zjmf_license){zend_hash_init(&key_fallbacks,8,NULL,ZVAL_PTR_DTOR,0);zend_hash_init(&original_urls,8,NULL,ZVAL_PTR_DTOR,0);request_ready=1;return SUCCESS;}
PHP_RSHUTDOWN_FUNCTION(zjmf_license){request_ready=0;zend_hash_destroy(&key_fallbacks);zend_hash_destroy(&original_urls);return SUCCESS;}
PHP_MINFO_FUNCTION(zjmf_license){php_info_print_table_start();php_info_print_table_row(2,"zjmf_license","enabled; cloud 3.9.42 / PHP 7.2 NTS");php_info_print_table_end();DISPLAY_INI_ENTRIES();}
zend_module_entry zjmf_license_module_entry={STANDARD_MODULE_HEADER,"zjmf_license",NULL,PHP_MINIT(zjmf_license),PHP_MSHUTDOWN(zjmf_license),PHP_RINIT(zjmf_license),PHP_RSHUTDOWN(zjmf_license),PHP_MINFO(zjmf_license),"1.1.0",STANDARD_MODULE_PROPERTIES};
ZEND_GET_MODULE(zjmf_license)
