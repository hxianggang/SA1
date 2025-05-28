<?php
function render_add_fun($row) {
    $post_id = $row['f_id'];
    ?>
    <div class='but-edit' onclick="openForm3(<?php echo $post_id; ?>)">新增紀錄</div>

    <div id="formContainer3_<?php echo $post_id; ?>" class="addmes_form-container-popup" style="display:none;">
        <form method="POST" action="fun_process_add.php">
            <label for="title">金額:</label>
            <input type="text" id="title" name="f_num" required><br>
            <label for="content">捐款者:</label>
            <input type="text" id="title" name="f_name" required><br><br>
            <input type="hidden" name="id" value="<?php echo $post_id; ?>"><br><br>

            <button type="submit">新增</button>
            <button type="button" onclick="closeForm3(<?php echo $post_id; ?>)">取消</button>
        </form>
    </div>
    <?php
}
?>
