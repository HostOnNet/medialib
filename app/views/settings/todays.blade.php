<div class="page-header">
    <h1>Todays</h1>
</div>

<form method="post" action="">
    <textarea name="todays" cols="50" rows="20"><?php echo Settings::get('todays') ?></textarea>
    <br />
    <br />
    <input type="submit" name="submit" value="Watch" class="btn btn-success btn-lg">
</form>
