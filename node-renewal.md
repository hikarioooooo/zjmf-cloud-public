# 节点自动续签

当前检查的原版 3.9.42 节点凭据单次有效七天。主控 `cloudgo-worker` 定期检查，成功签发超过约一天后会申请新凭据并下发，正常情况下不用每七天手动操作。

安装入口会检查主控续签服务和节点管理服务，并设置开机启动。主控出现 `NODE_AUTO_RENEWAL_READY` 表示服务检查通过，不代表已验证所有未来新增节点。

主控检查：

```bash
systemctl is-active cloudgo-worker
systemctl is-enabled cloudgo-worker
grep 'node_license_refresh' /var/log/cloudgo-worker.log | tail -n 10
```

节点检查：

```bash
systemctl is-active cloud-control
systemctl is-enabled cloud-control
cat /usr/local/zjmf/conf/node_license_status.json
```

关注 `last_success_time` 是否随续签更新，以及 `error_code`、`error_message` 是否为空。日志需结合时间判断，历史错误不代表当前仍失败。不要公开完整节点凭据。

2026-10-08 已完成两次受控实测：模拟凭据签发超过 25 小时，主控自动换发成功，无需手动下发。测试未等待七个自然日，也未覆盖所有断网情况。

这属于滚动续签，不是永久离线授权。保持主控、节点、授权站正常通信；持续故障超过凭据剩余有效期仍会影响使用。故障时先恢复服务与连接，再通过主控“下发授权”补救。
