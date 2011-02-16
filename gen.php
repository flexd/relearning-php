<?php
include 'config/db.inc';
require_once "inc/spyc.php";


// Taken from markdown-discount example, slightly modified for suitable use :-)
class Page extends MarkdownDocument {
  private $meta;
  public function __construct ($file) {
    parent::__construct();
    $this->file = 'posts/'.$file;
    $this->meta = array(); // Init meta array
  }
  public function define($variable, $value)
  {
    $this->meta[$variable] = $value;
  }
  public function render()
  {
    if (file_exists($this->file)) {
      // read the file
      $markdown = file_get_contents($this->file);
      // Get the YAML Front matter.
      list($yaml, $this->body) = preg_split("/---/", $markdown, 2, PREG_SPLIT_NO_EMPTY);
      #
      #$this->fmeta = syck_load($yaml);
      $this->fmeta = Spyc::YAMLLoad($yaml); // YAML?
      $this->meta = array_merge($this->fmeta, $this->meta);
      // replace {{ variable }} with front matter meta. Nothing happens if variable does not exist.
      if (preg_match_all("/\{\{ ([a-z]+) \}\}/", $this->body, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $v) {
          if (array_key_exists($v[1], $this->meta)) {
            if (is_array($this->meta[$v[1]])) {
              $this->meta[$v[1]] = implode($this->meta[$v[1]], ", "); // Handle keywords array [tag1, tag2, tag3]
            }
            // We have that key, replace the sucker!
            $this->body = preg_replace("/\{\{ $v[1] \}\}/", $this->meta[$v[1]], $this->body);
          }
        }
      }
      // Markdown stuff
      $inputMask = MarkdownDocument::NOHEADER | MarkdownDocument::TABSTOP;

      $this->initFromString((string)$this->body, 0 & $inputMask);
      $this->compile(((int) 0) & ~$inputMask);
      // spew out that generated HTML
      $hash = date("Ymd")."_".$this->meta['title'];
      #file_put_contents("html/$hash.html", $this->getHtml());
      print $this->getHtml();
    }
  }
}
?>
<?php
$dir = opendir('posts');
while(false != ($file = readdir($dir)))
{
  if(($file != ".") and ($file != ".."))
  {
    // Det er en fil vi vil ha!
    $p = new Page($file);
    $p->define('time', date("H:m:s", time()));
    $p->render();
  }
}
#foreach ($db->query("SELECT * FROM posts WHERE published=TRUE") as $row) {#
#  $p = new Page($row['filename']);
#  $p->define('time', date("H:m:s", time()));
#  $p->render();
#};
?>
