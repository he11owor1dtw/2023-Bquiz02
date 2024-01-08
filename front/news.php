<fieldset>
  <legend>目前位置:首頁 > 最新文章區</legend>
  <table style="width:95%;margin: auto;">
    <tr>
      <th width="30%">標題</th>
      <th width="60%">內容</th>
      <th width=""></th>
    </tr>
    <?php
    $total = $News->count();
    $div = 5;
    $pages = ceil($total / $div);
    $now = $_GET['p'] ?? 1;
    $start = ($now - 1) * $div;
    $rows = $News->all(" limit $start,$div");
    foreach ($rows as $row) {
    ?>
      <tr>
        <td><?= $row['title']; ?></td>
        <td><?= mb_substr($row['news'], 0, 30); ?>...</td>
        <td></td>
      </tr>
    <?php
    }
    ?>

    <?php
    if ($now - 1 > 0) {
      $prev = $now - 1;
      echo "<a href='?do=news&p=$prev'> ";
      echo " < ";
      echo " </a>";
    }
    for ($i = 1; $i <= $pages; $i++) {
      $size = ($i == $now) ? 'font-size:22px;' : 'font-size:16px;';
      echo "<a href='?do=news&p=$i' style='{$size}'> ";
      echo $i;
      echo " </a>";
    }
    if ($now + 1 <= $pages) {
      $next = $now + 1;
      echo "<a href='?do=news&p=$next'> ";
      echo " > ";
      echo " </a>";
    }
    ?>
  </table>
</fieldset>