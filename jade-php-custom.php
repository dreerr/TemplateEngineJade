<?php 
namespace Jade;
$srcPath = dirname(__FILE__) . '/jade-php/src/';
spl_autoload_register(function($class) use($srcPath) {
    if (! strstr($class, 'Jade')) return;
    include($srcPath . str_replace("\\", DIRECTORY_SEPARATOR, $class) . '.php');
});

class UnescapedCompiler extends Compiler {
    const ESCAPED = 'echo %s';
}

class CustomJade extends Jade {
    /**
     * Merge local options with constructor $options.
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
    	parent::__construct();
        $this->options = array_merge($this->options, $options);
    }

    /**
     * @param $input
     *
     * @return string
     */
    public function compile($input)
    {
        $parser = new Parser($input, null, $this->options['extension']);
        $compiler = $this->options['unescaped'] ? new UnescapedCompiler($this->options, $this->filters) : new Compiler($this->options, $this->filters);
        return $compiler->compile($parser->parse($input));
    }
}