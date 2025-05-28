<?php
function render_edit_fun($row) {
    $post_id = $row['f_id'];
    $title = $row['f_title'];
    $content = $row['f_content'];
    $money = $row['f_goal'];
    ?>
    <div class='but-edit' onclick="openForm2(<?php echo $post_id; ?>)">修改</div>

    <div id="formContainer2_<?php echo $post_id; ?>" class="addmes_form-container-popup" style="display:none;">
        <form method="POST" action="fun_process_edit.php">
            <label for="title">修改標題:</label>
            <input type="text" name="title" required value="<?php echo htmlspecialchars($title); ?>"><br>
            <label for="content">修改內文:</label>
            <textarea name="content" required><?php echo htmlspecialchars($content); ?></textarea><br>
            <label for="content">修改金額:</label>
            <textarea name="money" required><?php echo htmlspecialchars($money); ?></textarea><br>

            <input type="hidden" name="id" value="<?php echo $post_id; ?>"><br><br>

            <button type="submit">提交</button>
            <button type="button" onclick="closeForm2(<?php echo $post_id; ?>)">取消</button>
        </form>
    </div>
    <?php
}
?>
