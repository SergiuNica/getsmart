<?php
class NavMenu {
    private $id;
    private $menuItems = array();

    public function __construct($id='mainnav', $rows = null) {
        $this->id = $id;
        if(!empty($rows)) {
            loadRows($rows);
            
        }
        $this->setActive();
    }

    private function loadRows($rows) 
    {
        foreach($rows as $item) {
            $this->additem($item['key'], $item['label'], $item['order']);
        }
    }

    public function loadData($sql) 
    {
        //echo $sql;
        $pdo = $GLOBALS['pdo'];
        $menu_data = $pdo->query($sql);
        $this->loadRows($menu_data);
        
    }

    public function __toString()
  {
     // var_dump($this->menuItems);
    $output = '<nav id="' . $this->id . '">';
    foreach ($this->menuItems as $item) {
      $output .= '<a class="' . ($item['active'] ? 'active' : 'inactive') . '" href="?p=' . $item['key'] . '">' .
        $item['label'] . '</a>';
    }
    $output .= '</nav>';
    return $output;
  }

    public function setActive($key = ''){
        foreach ($this->menuItems as $i => $item) {
            if($item['key']==$key){
                $this->menuItems[$i]['active'] = true;
                } else {
                    $this->menuItems[$i]['active'] = false;
                }
        }
    }

    public function addItem($key, $label, $order=0){
        $key = trim(strtolower(str_replace( '','',$key)));
        $this->menuItems[] = array(
        'key'=>$key, 
        'label'=>$label, 
        'order'=>$order,
        'active'=>false);
        
        usort($this->menuItems, function($a, $b){
            if($a['order']==$b['order']){
            return 0;
            }
            if($a['order']>$b['order']){
                return 1;
                }
                return -1;
            });
            
    }


}

?>