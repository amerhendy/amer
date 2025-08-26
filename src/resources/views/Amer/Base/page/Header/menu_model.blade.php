<!-- menu_model -->
    <?php
            $menu=new Amerhendy\Amer\App\Models\Menu();
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
                            $link=$item->url;
                        }elseif($item->type == 'internal_link'){
                            if($item->url == '' || $item->url == NULL){$item->url=url('');}
                            $link=$item->url;
                        }elseif($item->type == 'email'){
                            $link="mailto:".$item->url;
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
                                print '<a class="dropdown-item" href="'.$b['url'].'" target="'.$b['link_target'].'"><span class="'.$b['icon'].'"></span>'.$b['title'].'</a>';
                            echo '</li>';
                        }
                    print '</ul>';
                }
            echo '</li>';

        }
    ?>
