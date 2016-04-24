# Work-Submission-Sys-for-lab
## 实验室作业提交系统
A system to upload homework for students,and also can allow faculty to publish assignments for students to view and download.
------------------------
* 由于系统是在使用投入使用一段时间后，才开始备份的。故数据库中已经含有部分数据。
* 清空数据库，删除down目录下的所有文件，并添加管理员账号即可正常使用。
* 注：<br/>
<ol type='1'>
<li>管理员账号密码需要经过SHA1()加密，user_type=1。<br/></li>
<li>用户只能通过管理员建立班级后添加。初始密码与账号相同<br/></li>
<li>上传作业后返回成功信息中的链接，包含中文，可能出现乱码导致找不到文件而无法下载。（WAMP开发环境中正常）（阿里云服务器中出现上述问题）<br/></li>
<li>上传文件的大小设置为5M，还需要设置服务器上传文件大小的设置。具体是设置Apache2/bin/php.ini中的<br/>
memory_limit = 300M <br/>
post_max_size = 200M<br/>
upload_max_filesize = 5M<br/></li>
</ol>
