<?php

/* edit_phone.html */
class __TwigTemplate_addb3f96797b9600cfe4cc26a2c8c336900a63e302b152e3778eadef46ac83c9 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("template.html", "edit_phone.html", 1);
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
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["phones"]) ? $context["phones"] : null), "LastName", array()), "html", null, true);
        echo " ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["phones"]) ? $context["phones"] : null), "FirstName", array()), "html", null, true);
        echo " ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["phones"]) ? $context["phones"] : null), "MiddleName", array()), "html", null, true);
        echo "
";
    }

    // line 7
    public function block_content($context, array $blocks = array())
    {
        // line 8
        echo "
<div class=\"container\">
\t<a href=\"/\" class=\"pull-left\">Вернуться на главную</a><br>

\t<h2>";
        // line 12
        echo twig_escape_filter($this->env, (isset($context["article"]) ? $context["article"] : null), "html", null, true);
        echo "</h2>
\t<form role=\"form\" action=\"/phones/update/\" method=\"POST\">
\t\t<input type=\"hidden\" name='id' value=\"";
        // line 14
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["phones"]) ? $context["phones"] : null), "id", array()), "html", null, true);
        echo "\">

\t\t<div class=\"form-group\">
\t\t\t<label for=\"last-name\">Фамилия</label>
\t\t\t<input type=\"text\" class=\"form-control\" id=\"last-name\" value=\"";
        // line 18
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["phones"]) ? $context["phones"] : null), "LastName", array()), "html", null, true);
        echo "\" name=\"LastName\" placeholder=\"Фамилия\">\t\t
\t\t</div>

\t\t<div class=\"form-group\">
\t\t\t<label for=\"first-name\">Имя</label>
\t\t\t<input type=\"text\" class=\"form-control\" id=\"first-name\" name=\"FirstName\" value=\"";
        // line 23
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["phones"]) ? $context["phones"] : null), "FirstName", array()), "html", null, true);
        echo "\" placeholder=\"Имя\">\t\t\t
\t\t</div>

\t\t<div class=\"form-group\">
\t\t\t<label for=\"middle-name\">Отчество</label>
\t\t\t<input type=\"text\" class=\"form-control\" id=\"middle-name\" name=\"MiddleName\" value=\"";
        // line 28
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["phones"]) ? $context["phones"] : null), "MiddleName", array()), "html", null, true);
        echo "\" placeholder=\"Отчество\">\t\t\t
\t\t</div>

\t\t<div class=\"form-group\">\t\t\t
\t\t\t<label for=\"birthday\">Дата рождения</label>
\t\t\t<input type=\"date\" class=\"form-control\" id=\"birthday\" name=\"Birthday\" value=\"";
        // line 33
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["phones"]) ? $context["phones"] : null), "Birthday", array()), "html", null, true);
        echo "\">\t\t\t
\t\t</div>

\t\t<div class=\"form-group\">
\t\t\t<label for=\"сity\">Город</label>
\t\t\t<input type=\"text\" class=\"form-control\" id=\"сity\" name=\"City\" value=\"";
        // line 38
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["phones"]) ? $context["phones"] : null), "City", array()), "html", null, true);
        echo "\" placeholder=\"Город\">\t\t\t
\t\t</div>

\t\t<div class=\"form-group\">
\t\t\t<label for=\"Street\">Улица</label>
\t\t\t<input type=\"text\" class=\"form-control\" id=\"street\" name=\"Street\" value=\"";
        // line 43
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["phones"]) ? $context["phones"] : null), "Street", array()), "html", null, true);
        echo "\" placeholder=\"Улица\">\t\t\t
\t\t</div>

\t\t<div class=\"form-group\">
\t\t\t<label for=\"home\">Дом</label>
\t\t\t<input type=\"number\" class=\"form-control\" id=\"home\" name=\"Home\" value=\"";
        // line 48
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["phones"]) ? $context["phones"] : null), "Home", array()), "html", null, true);
        echo "\" placeholder=\"Дом\">
\t\t</div>

\t\t<div class=\"form-group\">
\t\t\t<label for=\"appartment\">Квартира</label>
\t\t\t<input type=\"number\" class=\"form-control\" id=\"appartment\" name=\"Appartment\" value=\"";
        // line 53
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["phones"]) ? $context["phones"] : null), "Appartment", array()), "html", null, true);
        echo "\" placeholder=\"Квартира\">\t\t\t
\t\t</div>

\t\t<div class=\"form-group\">
\t\t\t<label for=\"phone\">Телефон</label>
\t\t\t<input type=\"number\" class=\"form-control\" id=\"phone\" name=\"PhoneNumber\" value=\"";
        // line 58
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["phones"]) ? $context["phones"] : null), "PhoneNumber", array()), "html", null, true);
        echo "\" placeholder=\"Телефон\">
\t\t</div>

\t\t<button type=\"submit\" class=\"btn btn-primary\" name=\"submitChange\">Изменить</button>
\t</form>
</div>

";
    }

    public function getTemplateName()
    {
        return "edit_phone.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  127 => 58,  119 => 53,  111 => 48,  103 => 43,  95 => 38,  87 => 33,  79 => 28,  71 => 23,  63 => 18,  56 => 14,  51 => 12,  45 => 8,  42 => 7,  32 => 4,  29 => 3,  11 => 1,);
    }
}
/* {% extends "template.html" %}*/
/* */
/* {% block title %}*/
/* {{ phones.LastName }} {{ phones.FirstName }} {{ phones.MiddleName }}*/
/* {% endblock %}*/
/* */
/* {% block content %}*/
/* */
/* <div class="container">*/
/* 	<a href="/" class="pull-left">Вернуться на главную</a><br>*/
/* */
/* 	<h2>{{ article }}</h2>*/
/* 	<form role="form" action="/phones/update/" method="POST">*/
/* 		<input type="hidden" name='id' value="{{ phones.id }}">*/
/* */
/* 		<div class="form-group">*/
/* 			<label for="last-name">Фамилия</label>*/
/* 			<input type="text" class="form-control" id="last-name" value="{{ phones.LastName }}" name="LastName" placeholder="Фамилия">		*/
/* 		</div>*/
/* */
/* 		<div class="form-group">*/
/* 			<label for="first-name">Имя</label>*/
/* 			<input type="text" class="form-control" id="first-name" name="FirstName" value="{{ phones.FirstName }}" placeholder="Имя">			*/
/* 		</div>*/
/* */
/* 		<div class="form-group">*/
/* 			<label for="middle-name">Отчество</label>*/
/* 			<input type="text" class="form-control" id="middle-name" name="MiddleName" value="{{ phones.MiddleName }}" placeholder="Отчество">			*/
/* 		</div>*/
/* */
/* 		<div class="form-group">			*/
/* 			<label for="birthday">Дата рождения</label>*/
/* 			<input type="date" class="form-control" id="birthday" name="Birthday" value="{{ phones.Birthday }}">			*/
/* 		</div>*/
/* */
/* 		<div class="form-group">*/
/* 			<label for="сity">Город</label>*/
/* 			<input type="text" class="form-control" id="сity" name="City" value="{{ phones.City }}" placeholder="Город">			*/
/* 		</div>*/
/* */
/* 		<div class="form-group">*/
/* 			<label for="Street">Улица</label>*/
/* 			<input type="text" class="form-control" id="street" name="Street" value="{{ phones.Street }}" placeholder="Улица">			*/
/* 		</div>*/
/* */
/* 		<div class="form-group">*/
/* 			<label for="home">Дом</label>*/
/* 			<input type="number" class="form-control" id="home" name="Home" value="{{ phones.Home }}" placeholder="Дом">*/
/* 		</div>*/
/* */
/* 		<div class="form-group">*/
/* 			<label for="appartment">Квартира</label>*/
/* 			<input type="number" class="form-control" id="appartment" name="Appartment" value="{{ phones.Appartment }}" placeholder="Квартира">			*/
/* 		</div>*/
/* */
/* 		<div class="form-group">*/
/* 			<label for="phone">Телефон</label>*/
/* 			<input type="number" class="form-control" id="phone" name="PhoneNumber" value="{{ phones.PhoneNumber }}" placeholder="Телефон">*/
/* 		</div>*/
/* */
/* 		<button type="submit" class="btn btn-primary" name="submitChange">Изменить</button>*/
/* 	</form>*/
/* </div>*/
/* */
/* {% endblock %}*/
