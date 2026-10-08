**🌏 购买香港、美国、台湾等宿主机，请联系：📩 TG：@gfw301_bot**

# 魔方云 3.9.42 开心版

包含安装站和独立授权站，按下面步骤搭建即可。

**主控系统：使用本套脚本时，建议选择 CentOS Stream 8（x86_64）。** 该系统[已停止维护](https://www.centos.org/centos-linux/)。

[点这里下载安装站包和授权站包](https://github.com/hikarioooooo/zjmf-cloud-public/releases/latest)

## 第一步：搭建安装站

1. 在宝塔里新建一个网站，选择 PHP 7.2 或以上版本。
2. 下载 `zjmf-install-site-github.zip`，上传并解压到网站目录。
3. 打开包里的 `nginx-rewrite.conf`，把内容填到这个网站的“伪静态”设置中，保存。已有相同规则时不要重复添加。
4. 打开网站，看到安装教程和安装命令就可以了，不用自己修改脚本里的域名。

## 第二步：搭建授权站

1. 在宝塔里再建一个 PHP 网站，使用另一个域名。
2. 下载 `zjmf-auth-site-latest.zip`，解压后把 `index.php` 上传到这个网站目录。
3. 把包里 `伪静态.txt` 的内容填到这个网站的“伪静态”设置中，保存。
4. 在 PHP 设置中开启 `openssl` 和 `allow_url_fopen`，网站目录设为网站用户可写。
5. 打开 `你的授权站地址/health`，看到 `status: 200` 就可以了。记下完整地址，例如 `https://auth.example.com/`。

## 第三步：安装魔方云

1. 用 SSH 登录要安装魔方云的服务器，使用 root 账号。
2. 打开第一步搭好的安装站，复制首页的安装命令，粘贴到 SSH 里执行。
3. 提示输入授权站地址时，填写第二步搭好的地址。
4. 按需要选择安装类型：
   - 只装管理后台：选“主控”。
   - 只装运行虚拟机的服务器：选“仅计算节点”。
   - 一台机器两个都装：选“合并安装”。
5. 授权码填写 **32 位大写 MD5**，只包含数字 `0–9` 和大写字母 `A–F`。下面这串**只是格式示例**，请使用自己生成的结果：

   ```text
   7D1C62A93F804BE69A5D28C041E7B936
   ```

   首次安装可以在服务器执行下面这条命令，复制输出的 32 位结果，作为授权码并保存。已有授权码的继续使用原来的：

   ```bash
   cat /proc/sys/kernel/random/uuid | md5sum | cut -c 1-32 | tr 'a-f' 'A-F'
   ```

6. 等到脚本全部结束，保存后台地址、账号和密码。出现 `STOPPED` 就先处理报错，不要当作安装成功。
7. 登录后台。单独安装的节点，在主控里添加，确认显示“已连接”。

**主控和节点都用同一条安装命令，填写同一个授权站地址。**

## 第四步：节点断线或没授权时怎么处理

1. 重新登录节点，查看 `/var/log/zjmf-bootstrap/` 里的最新安装日志；如果还在安装，先等它结束。
2. 确认节点已经装好后，在**节点服务器**执行下面的补配命令。把 `https://install.example.com` 换成你的**安装站地址**，把 `https://auth.example.com/` 换成主控正在使用的**授权站地址**：

   ```bash
   wget 'https://install.example.com/install-zjmf-cloud_new' -O zjmf-node-repair.sh && bash zjmf-node-repair.sh --node-only --auth-url 'https://auth.example.com/'
   ```

3. 回到主控查看节点。仍然没授权时，点一次“下发授权”。
4. 平时保持主控、节点和授权站正常运行，不用每周手动续签。

## 第五步：以后更新或更换授权站

### 只更新授权站文件

1. 先备份授权站的 `index.php` 和 `.zjmf-data` 文件夹。
2. 覆盖新版 `index.php`，保留原来的 `.zjmf-data`。
3. 打开 `你的授权站地址/health`，确认显示 `status: 200`。

### 更换授权站地址

下面的 `https://install.example.com` 换成你的**安装站地址**，`https://new-auth.example.com/` 换成**新授权站地址**。选业务空闲时操作，切换前先备份主控数据库和配置。

1. 先搭好新授权站，暂时保留旧授权站。在**主控服务器**执行下面命令检查新站，检查通过后再继续：

   ```bash
   wget 'https://install.example.com/install-zjmf-cloud_new' -O zjmf-auth-change.sh && bash zjmf-auth-change.sh --auth-test --auth-url 'https://new-auth.example.com/'
   ```

2. 在同一台**主控服务器**执行下面命令，切换到新地址。主控和节点装在同一台的，也用这一条；按提示填写后台登录信息：

   ```bash
   bash zjmf-auth-change.sh --existing --auth-url 'https://new-auth.example.com/'
   ```

3. 在**每一台单独安装的节点服务器**上执行下面命令，填写同一个新地址：

   ```bash
   wget 'https://install.example.com/install-zjmf-cloud_new' -O zjmf-auth-change.sh && bash zjmf-auth-change.sh --node-only --auth-url 'https://new-auth.example.com/'
   ```

4. 回到后台，逐个确认节点“已连接”且授权正常，再停用旧授权站。

**以上命令用于已安装的 3.9.42。3.9.22 等旧版先不要运行，这不是旧版升级命令。**
