<?php
function render_edit_form($row) {
    $post_id = $row['id'];
    $title = $row['title'];
    $content = $row['content'];
    ?>
    <div class='but-edit' onclick="openForm2(<?php echo $row['id']; ?>)">修改</div>

    <div id="formContainer2_<?php echo $row['id']; ?>" class="addmes_form-container-popup" style="display:none;">
        <form method="POST" action="ann_process_edit.php">
            <label for="title">修改公告:</label>
            <input type="text" name="title" required value="<?php echo htmlspecialchars($title); ?>"><br><br>
            <label for="content">內文:</label>
            <textarea name="content" required><?php echo htmlspecialchars($content); ?></textarea><br><br>

            <input type="hidden" name="id" value="<?php echo $post_id; ?>"><br><br>

            <button type="submit">提交</button>
            <button type="button" onclick="closeForm2(<?php echo $row['id']; ?>)">取消</button>
        </form>
    </div>
    <?php
}
?>
