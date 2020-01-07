<?php

namespace Models;

class User extends Model
{
    protected $tblname='users';
    protected $findingbase='tid';
    protected $idcol='id';
    public const START=0;
    public const WAITFORLINK=1;
    public const WAITFORRHASH=2;
    public const WAITFORTEXT=3;
    public function __construct($id="")
    {
        if(!empty($id)) {
            $db=new Db();
            $db->settbl($this->tblname);
            $instance=$db->search_data($this->idcol,$id);
            if(is_object($instance)){
                $vars=get_object_vars($instance);
                foreach($vars as $key=>$value){
                    $this->$key=$value;
                }
                return $this;
            }else{
                return null;
            }
        }else{
            return $this;
        }
    }
    public function updateStep($step)
    {
        $updates=array('step'=>$step);
        $name=$this->idcol;
        $this->update($updates);
        $updatedUser=new User($this->$name);
        return $updatedUser;
    }
    public function forwardStep()
    {
        switch ($this->step)
        {
            case self::START:
                return $this->updateStep(self::WAITFORLINK);
                break;
            case self::WAITFORLINK:
                return $this->updateStep(self::WAITFORRHASH);
                break;
            case self::WAITFORRHASH:
                return $this->updateStep(self::WAITFORTEXT);
                break;
            case self::WAITFORTEXT:
                return $this->updateStep(self::START);
                break;
        }
    }
    public function backwardStep()
    {
        switch ($this->step)
        {
            case self::START:
                return $this->updateStep(self::START);
                break;
            case self::WAITFORLINK:
                return $this->updateStep(self::START);
                break;
            case self::WAITFORRHASH:
                return $this->updateStep(self::START);
                break;
            case self::WAITFORTEXT:
                return $this->updateStep(self::WAITFORLINK);
                break;
        }
    }
}
