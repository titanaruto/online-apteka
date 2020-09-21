<?
?><script type="text/javascript">
function send() {
	window.location = 'http://online-apteka.com.ua/personal/order/payment/?ORDER_ID=<?=$_GET['ORDER_ID']?>&CONFIRM=Y';
}
</script>
<input id="confirm" name="CONFIRM" type="checkbox" value="<?=$_GET['ORDER_ID'];?>">
<button onclick="send();"> Я СОГЛАСЕН / СОГЛАСНА </button>
