<?=form_open_multipart('file/upload');?>
    <div class="form-group">
        <label for="userfile">File:</label>
        <input type="file" name="userfile" id="userfile">
        <p class="help-block"><?=$error;?></p>
    </div>
    <input type="submit" value="Upload" class="btn btn-default">
</form>