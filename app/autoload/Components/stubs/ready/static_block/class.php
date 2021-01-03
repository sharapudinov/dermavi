<?php

class StaticBlockComponent extends CBitrixComponent
{
    public function executeComponent ()
    {
        $this->arResult = $this->arParams;
        $this->includeComponentTemplate();
    }
}