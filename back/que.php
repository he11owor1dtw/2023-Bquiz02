<style>
  legend {
    font-size: larger;
    font-weight: bolder;
  }
</style>
<fieldset>
  <form action="./api/add_que.php" method="post" style="text-align:center">
    <legend>新增問卷</legend>
    <div style=" display:flex text-align-center">
      <div>問卷名稱</div>
      <div>
        <input type="text" name="subject">
      </div>
    </div>
    <div style="display:flex text-align-center">
      <div id="opt">選項
        <input type="text" name="option[]">
        <input type="button" value="更多" onclick="more()">
      </div>
    </div>
    <div class="ct">
      <input type="submit" value="送出">
      <input type="reset" value="清空">
    </div>
  </form>
</fieldset>
<script>
  function more() {
    let opt = ` <div id="opt">選項
      <input type="text" name="option[]">
      </div>`
    $("#opt").before(opt);
  }
</script>