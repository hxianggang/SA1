<!--0503 AQ 撰寫修改公告-->

<?php
    include('db.php');
    $post_id=$_GET['id'];
    $sql = "select * from announcements where id='$post_id'";
    $result=mysqli_query($conn,$sql);
    if($row=mysqli_fetch_assoc($result)){
        $title = $row['title'];
        $content = $row['content'];
    }
    
?>

<div id="formContainer" class="addmes_form-container-popup" style="">
    <form id="suggestionForm" method="POST" action="ann_process_edit.php">
        <label for="title">修改公告:</label>
        <input type="text" id="title" name="title" required value="<?php echo $title;?>"><br><br>

        <label for="content">內文:</label>
        <textarea id="content" name="content" required><?php echo $content; ?></textarea><br><br>

        <input type="hidden" id="id" name="id" required value="<?php echo $post_id;?>"><br><br>

        <button type="submit">提交</button>
        <button type="button" onclick="closeForm()">取消</button>
    </form>
</div>