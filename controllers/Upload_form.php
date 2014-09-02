<?php
/**
 * Upload_form
 * 
 * 檔案上傳
 *
 * @author pudding
 */
class Upload_form {
    
    /**
     * 顯示上傳檔案的位置
     * @version 20140818 Pulipuli Chen
     */
    function display_view($f3) {
        
        // 要設定這個header，才能做iframe
        header('X-Frame-Options: ');
        
        //$file = R::dispense("file");
        //$file->title="哈利波特";
        //$id = R::store($file);
                
        $this->get_how_to_use_content($f3);
        $this->get_readme_update_year($f3);
        
        $template = new Template;
        echo $template->render("upload_form.html");
        
        //echo $f3->get("URI");
        
        //echo "upload form " . $id;
    }
    
    /**
     * 顯示上傳檔案的位置
     * @version 20140818 Pulipuli Chen
     */
    function display_view_simple($f3) {
        
        // 要設定這個header，才能做iframe
        header('X-Frame-Options: ');
        
        //$file = R::dispense("file");
        //$file->title="哈利波特";
        //$id = R::store($file);
                
        //$this->get_how_to_use_content($f3);
        //$this->get_readme_update_year($f3);
        
        $template = new Template;
        echo $template->render("upload_form_simple.html");
        
        //echo $f3->get("URI");
        
        //echo "upload form " . $id;
    }
    
    private function get_how_to_use_content($f3) {
        $content = "How to use content.";
        
        $file = F3::instance()->read('help/README.md');
        $html = Markdown::instance()->convert($file);
        
        $f3->set('how_to_use_content', $html);
        return $content;
    }
    
    private function get_readme_update_year($f3) {
        // @TODO get_readme_update_year
        $year = '2014';
        $f3->set('readme_update_year', $year);
    }
}
