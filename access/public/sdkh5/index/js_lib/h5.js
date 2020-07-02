function H5GNSDK(opts) {
    var options = {
     
      /**
       * �û���½�ɹ��ص���������Object data����
       */
    //  onLogin: opts['onLogin'] || function (data) {  alert("δʵ�ֵ�¼�ص��ķ���");},
  
 
      onPayComplete: opts['onPayComplete'] || function (data) { },
    };
  
    var isReady = false;
  
    var isEnterGame = false;
  
    var debug = function (title, msg) {
      if (!options.debug) return;
      alert(title);
      console.log(title);
      console.log(msg);
    };
  
    var postMessage = function (event, data) {
      debug('����postMessage:[' + event + ']', {
        event: event,
        data: data
      });
  
      window.parent.postMessage({
        event: event,
        data: data
      }, '*');
    };
  
 
  
  /**
  *   orderinfo ��������
    *       product_name     ��Ʒ����
    *       product_id       ��Ʒid
    *       product_desc     ��Ʒ����
    *       exchange_rate    ����Ҷһ�����������100����ʾ1Ԫ����100����ң� Ĭ��Ϊ0��
    *       ext    CP�Զ�����չ�ֶ� ͸����Ϣ Ĭ��Ϊ����
    *       currency_name    ��������ƣ����ҡ�Ԫ����Ĭ��Ϊ����
    *       product_count    ��Ʒ����(������Ϸ��Ҫ֧��һ�ι�������Ʒ),Ĭ��Ϊ1
    *       product_price    �۸�(Ԫ),����λС��
    *      cp_order_id    ����Ҷһ�����������100����ʾ1Ԫ����100����ң� Ĭ��Ϊ0��
  *   app_id  ��Ϸid
  *   timestamp  �ͻ���ʱ���
  *   roleinfo  ��ɫ����
    *   party_name  ���ᡢ�������ƣ����û�У�������ַ�������
    *   role_vip  ���vip�ȼ������û�У�����0��
    *   role_balence  �����Ϸ����Ϸ��������λС��;���û���˻�������0��
    *   rolelevel_ctime  ��Ҵ�����ɫ��ʱ�� ʱ���(11λ����������λ��)��Ĭ��0
    *   rolelevel_mtime  ��ҽ�ɫ�ȼ��仯ʱ�� ʱ���(11λ����������λ��)��Ĭ��0
    *   server_name  ��Ϸ����������
    *   role_name  ��ҽ�ɫ����
    *   server_id  ��Ϸ������id
    *   role_id  ��ҽ�ɫid
    *   role_level  ��ҽ�ɫ�ȼ������û�У�����0��
  *   sign  �ӿ� sign
  */
    this.pay = function(payData) {
      if (!isReady) return;
  
      fee = parseFloat(payData.orderinfo.product_price);
      if (isNaN(fee) || fee <= 0) {
        alert("֧������쳣��" + fee);
      }
 
      postMessage('fastPay', payData);
    };
 
  
 

    this.logout =function(){
        postMessage('logout', {});
    };
  
    this.ready = function () {
      if (!isReady) {
        isReady = true;
        postMessage('ready', {});
      }
    };
 
    /**
     * ��������sdk��postMessage
     * e = {event: "<event>", data: <data>}
     */
    var receive = function (e) {
      var data = e.data;
  
      debug('���յ�postMessage', e);
  
      switch (data.event) {
        case 'onPayComplete':
          options.onPayComplete(data.data);
          break;
        case 'onLogin':
          options.onLogin(data.data);
          break;
        default:
          debug('unknowEvent', {
            event: data.event,
            data: data.data
          });
      }
    };

    window.addEventListener('message', receive, false);
  }