<?php

/* registration.html */
class __TwigTemplate_5354578cf2e60cb3ec2a961c585745e526057f8fb1b7dbcd0a94a36f1a398eaf extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("template.html", "registration.html", 1);
        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "template.html";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    public function block_title($context, array $blocks = array())
    {
        echo twig_escape_filter($this->env, (isset($context["title"]) ? $context["title"] : null), "html", null, true);
    }

    // line 3
    public function block_content($context, array $blocks = array())
    {
        // line 4
        echo "<div class=\"container col-xs-6\">
    ";
        // line 5
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["errors"]) ? $context["errors"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["error"]) {
            // line 6
            echo "    <div class=\"alert alert-danger\" role=\"alert\"> ";
            echo twig_escape_filter($this->env, $context["error"], "html", null, true);
            echo " </div>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['error'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 8
        echo "    <h2>";
        echo twig_escape_filter($this->env, (isset($context["title"]) ? $context["title"] : null), "html", null, true);
        echo "</h2>
    <form action=\"/users/createUser/\" method=\"post\">
        <div class=\"form-group\">
            <label for=\"username\">Лоnt</label>
            <input type=\"text\" class=\"form-control\" id=\"username\" value=\"\" name=\"username\">
            <label for=\"password\">Пароль</label>
            <input type=\"password\" class=\"form-control\" id=\"password\" value=\"\" name=\"password\">
        </div>
        <button type=\"submit\" class=\"btn btn-default\" name=\"register\">Зарегестрироваться</button>
    </form>
</div>
";
    }

    public function getTemplateName()
    {
        return "registration.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  54 => 8,  45 => 6,  41 => 5,  38 => 4,  35 => 3,  29 => 2,  11 => 1,);
    }
}
/* {% extends "template.html" %}*/
/* {% block title %}{{ title }}{% endblock %}*/
/* {% block content %}*/
/* <div class="container col-xs-6">*/
/*     {% for error in errors %}*/
/*     <div class="alert alert-danger" role="alert"> {{ error }} </div>*/
/*     {% endfor %}*/
/*     <h2>{{ title }}</h2>*/
/*     <form action="/users/createUser/" method="post">*/
/*         <div class="form-group">*/
/*             <label for="username">Лоnt</label>*/
/*             <input type="text" class="form-control" id="username" value="" name="username">*/
/*             <label for="password">Пароль</label>*/
/*             <input type="password" class="form-control" id="password" value="" name="password">*/
/*         </div>*/
/*         <button type="submit" class="btn btn-default" name="register">Зарегестрироваться</button>*/
/*     </form>*/
/* </div>*/
/* {% endblock %}*/
