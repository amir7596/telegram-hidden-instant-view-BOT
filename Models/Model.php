<?php
namespace Models;
class Model
{
    public static function create($data)
    {
        $db=new Db();
        $self=new static;
        $cls=get_class($self);
        $db->settbl($self->tblname);
        $id=Myutils::getNext($self->tblname);
        $db->add_data($data);
        $instance=new $cls($id);
        return $instance;
    }
    public static function find($item)
    {
        $db=new Db();
        $self=new static;
        $cls=get_class($self);
        $db->settbl($self->tblname);
        $instance=$db->search_data($self->findingbase,$item);
        if(is_object($instance)){
            $name=$self->idcol;
            $instance=new $cls($instance->$name);
            return $instance;
        }else{
            return null;
        }
    }
    public function update($update)
    {
        $db=new Db();
        $db->settbl($this->tblname);
        $name=$this->idcol;
        $cdata=array(
          $this->idcol=>$this->$name,
        );
        $db->update_data($update,$cdata);
    }
}
