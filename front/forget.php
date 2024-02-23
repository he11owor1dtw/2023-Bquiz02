<fieldset style=" margin: auto;text-align: center;">
  <legend>忘記密碼</legend>
  <div>請輸入信箱以查詢密碼</div>
  <div>
    <input type="text" name="email" id="email" style="width: 250px;">
  </div>
  <div id="result"></div>
  <div>
    <button onclick="forget()">找回密碼</button>
  </div>
</fieldset>

<script>
  function forget() {
    $.get("./api/forget.php", {
      email: $("#email").val()
    }, (res) => {
      $("#result").text(res)
    })
  }
</script>