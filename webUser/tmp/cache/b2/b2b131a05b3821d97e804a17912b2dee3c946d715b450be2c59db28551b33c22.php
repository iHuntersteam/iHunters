<?php

/* template.html */
class __TwigTemplate_a26f9f6025e3458e0614cbf9789f972b091ee7859ed186478f36e44df98706bc extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html>
<head>
\t<meta charset=\"utf-8\">
\t<title>";
        // line 5
        $this->displayBlock('title', $context, $blocks);
        echo "</title>
\t<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css\">

</head>
<body>
";
        // line 10
        $this->displayBlock('content', $context, $blocks);
        // line 12
        echo "</body>
</html>";
    }

    // line 5
    public function block_title($context, array $blocks = array())
    {
    }

    // line 10
    public function block_content($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "template.html";
    }

    public function getDebugInfo()
    {
        return array (  47 => 10,  42 => 5,  37 => 12,  35 => 10,  27 => 5,  21 => 1,);
    }
}
/* <!DOCTYPE html>*/
/* <html>*/
/* <head>*/
/* 	<meta charset="utf-8">*/
/* 	<title>{% block title %}{% endblock %}</title>*/
/* 	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">*/
/* */
/* </head>*/
/* <body>*/
/* {% block content %}*/
/* {% endblock %}*/
/* </body>*/
/* </html>*/
