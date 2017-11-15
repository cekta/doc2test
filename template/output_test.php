    function <?=$name?>() {
        $this->expectOutputString(<?=$this->v($expected)?>);
        require __DIR__ . '/' . <?=$this->v($test_name)?> . '/' . <?=$this->v($code_file)?>;
    }