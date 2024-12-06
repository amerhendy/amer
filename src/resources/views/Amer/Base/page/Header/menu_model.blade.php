<!-- menu_model -->
    <?php
        $menu=new Amerhendy\Amer\App\Models\menu();
        foreach($menu::getTree() as $b){
            $item=$b;
            $children=$b->children->toArray();
            $dropclass=''; $dropclasstog='';
            if(count($children)){
                $dropclass="dropdown";$dropclasstog='dropdown-toggle';
            }
            echo '<li class="nav-item white-text '.$dropclass.'">';
                echo'<a class="nav-link '.$dropclasstog.'"';
                if(count($children)){
                    print ' href="#" id="navbarDropdown_'.$item->id.'" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"';
                    }else{
                        if($item->type == 'external_link'){
                            $link=$item->link;
                        }elseif($item->type == 'internal_link'){
                            if($item->link == '' || $item->link == NULL){$item->link=url('');}
                            $link=$item->link;
                        }elseif($item->type == 'email'){
                            $link="mailto:".$item->link;
                        }elseif($item->type == 'nolink'){
                            $link='#';
                        }
                        if($item->target == 'popup'){
                            print ' onclick="popitup(\''.$link.'\',\''.$item->title.'\');"';
                        }else{
                            print  ' href="'.$link.'" target="'.$item->target.'"';
                        }
                    }
                echo'>';
                print '<span class="'.$item->icon.'"></span>'.$item->title;
                echo'</a>';
                if(count($children)){
                    print '<ul class="dropdown-menu text-right" aria-labelledby="navbarDropdown_'.$item->id.'">';
                        foreach($children as $b) {
                            echo '<li>';
                                print '<a class="dropdown-item" href="'.$b['link'].'" target="'.$b['target'].'"><span class="'.$b['icon'].'"></span>'.$b['title'].'</a>';
                            echo '</li>';
                        }
                    print '</ul>';
                }
            echo '</li>';

        }
    ?>
