@echo off
SET BASEPATH=E:\PHPProject\sdk\access\public
rem ����ѹ��JS�ļ��ĸ�Ŀ¼���ű����Զ�������β��Һ�ѹ�����е�JS
SET JSFOLDER=%BASEPATH%h5\index\js
rem �������js����·��
SET JSMESSPATH=%BASEPATH%\sdkh5\index\js\
xcopy %BASEPATH%h5 %BASEPATH%\sdkh5 /s /e /y
echo looking for js file
chdir /d %JSFOLDER%
for /r  . %%a in (*.js)  do (
	echo  messing %%~nxa
	uglifyjs %%a  -m -c -e -o %JSMESSPATH%%%~nxa
)
rem  uglifyjs %BASEPATH%\login.php  -m -c -e -o %JSMESSPATH%\login.php
echo success
