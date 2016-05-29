<?php

/* phonebook.html */
class __TwigTemplate_ddf7ad03fff8128686d0fe454fd86c6706c8f8c6e51e4de88e4dd398fdd7339e extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("template.html", "phonebook.html", 1);
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

    // line 3
    public function block_title($context, array $blocks = array())
    {
        // line 4
        echo twig_escape_filter($this->env, (isset($context["title"]) ? $context["title"] : null), "html", null, true);
        echo "
";
    }

    // line 7
    public function block_content($context, array $blocks = array())
    {
        // line 8
        echo "
<div class=\"container\">
<a href=\"/phones/addc\" class=\"btn btn-primary\">Добавить</a>
\t<h1>Телефонный справочник</h1>
\t";
        // line 12
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["phones"]) ? $context["phones"] : null));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["phone"]) {
            // line 13
            echo "\t<div class=\"article\">
\t\t<p><b>ФИО:</b> ";
            // line 14
            echo twig_escape_filter($this->env, $this->getAttribute($context["phone"], "LastName", array()), "html", null, true);
            echo " ";
            echo twig_escape_filter($this->env, $this->getAttribute($context["phone"], "FirstName", array()), "html", null, true);
            echo " ";
            echo twig_escape_filter($this->env, $this->getAttribute($context["phone"], "MiddleName", array()), "html", null, true);
            echo "</p>
\t\t<p><b>Дата рождения:</b> ";
            // line 15
            echo twig_escape_filter($this->env, $this->getAttribute($context["phone"], "Birthday", array()), "html", null, true);
            echo "</p>
\t\t<p><b>Адрес:</b> ";
            // line 16
            echo twig_escape_filter($this->env, $this->getAttribute($context["phone"], "City", array()), "html", null, true);
            echo ", ул. ";
            echo twig_escape_filter($this->env, $this->getAttribute($context["phone"], "Street", array()), "html", null, true);
            echo ", д. ";
            echo twig_escape_filter($this->env, $this->getAttribute($context["phone"], "Home", array()), "html", null, true);
            echo ", кв. ";
            echo twig_escape_filter($this->env, $this->getAttribute($context["phone"], "Appartment", array()), "html", null, true);
            echo "</p>
\t\t<p><b>Телефон:</b> ";
            // line 17
            echo twig_escape_filter($this->env, $this->getAttribute($context["phone"], "PhoneNumber", array()), "html", null, true);
            echo "</p>
\t\t<a href=\"/phones/show/";
            // line 18
            echo twig_escape_filter($this->env, $this->getAttribute($context["phone"], "id", array()), "html", null, true);
            echo "\" class=\"btn btn-default\">Редактировать</a>
\t\t<a href='/phones/delete/";
            // line 19
            echo twig_escape_filter($this->env, $this->getAttribute($context["phone"], "id", array()), "html", null, true);
            echo "' class=\"btn btn-danger pull-right\">Удалить</a>
\t\t<hr>
\t</div>

";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 24
            echo "<p>Нет номеров</p>
";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['phone'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 26
        echo "</div>
";
    }

    public function getTemplateName()
    {
        return "phonebook.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  102 => 26,  95 => 24,  85 => 19,  81 => 18,  77 => 17,  67 => 16,  63 => 15,  55 => 14,  52 => 13,  47 => 12,  41 => 8,  38 => 7,  32 => 4,  29 => 3,  11 => 1,);
    }
}
/* {% extends "template.html" %}*/
/* */
/* {% block title %}*/
/* {{ title }}*/
/* {% endblock %}*/
/* */
/* {% block content %}*/
/* */
/* <div class="container">*/
/* <a href="/phones/addc" class="btn btn-primary">Добавить</a>*/
/* 	<h1>Телефонный справочник</h1>*/
/* 	{% for phone in phones %}*/
/* 	<div class="article">*/
/* 		<p><b>ФИО:</b> {{ phone.LastName }} {{ phone.FirstName }} {{ phone.MiddleName }}</p>*/
/* 		<p><b>Дата рождения:</b> {{ phone.Birthday }}</p>*/
/* 		<p><b>Адрес:</b> {{ phone.City }}, ул. {{ phone.Street }}, д. {{ phone.Home }}, кв. {{ phone.Appartment }}</p>*/
/* 		<p><b>Телефон:</b> {{ phone.PhoneNumber }}</p>*/
/* 		<a href="/phones/show/{{ phone.id }}" class="btn btn-default">Редактировать</a>*/
/* 		<a href='/phones/delete/{{ phone.id }}' class="btn btn-danger pull-right">Удалить</a>*/
/* 		<hr>*/
/* 	</div>*/
/* */
/* {% else %}*/
/* <p>Нет номеров</p>*/
/* {% endfor %}*/
/* </div>*/
/* {% endblock %}*/
