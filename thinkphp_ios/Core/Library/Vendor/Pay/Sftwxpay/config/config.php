<?php
class Config{
    private $cfg = array(
		//�ӿ������ַ���̶����䣬�����޸�
        'url'=>'https://pay.swiftpass.cn/pay/gateway',
		//�����̻��ţ��̻����Ϊ�Լ���
        'mchId'=>'105520043680',
		//������Կ���̻����Ϊ�Լ���
        'key'=>'14254dffc4c23f5bb8f2c6b5ffe9eadf',
		//�汾��Ĭ��2.0
        'version'=>'2.0'
       );
    
    public function C($cfgName){
        return $this->cfg[$cfgName];
    }
}
?>