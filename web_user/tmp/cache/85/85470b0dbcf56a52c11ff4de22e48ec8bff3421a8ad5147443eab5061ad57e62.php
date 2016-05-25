<?php

/* add_phone.html */
class __TwigTemplate_818f145edb3f2633ac86db2b35c037f94e1e8bde1f22c64b05d9f0044e955551 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("template.html", "add_phone.html", 1);
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
        echo "Добавить нового пользователя
";
    }

    // line 7
    public function block_content($context, array $blocks = array())
    {
        // line 8
        echo "
<div class=\"container\">
\t<a href=\"/\" class=\"pull-left\">Вернуться на главную</a><br>
<div class=\"address\">
            <h1>Форма для ввода адреса</h1>
            <form class=\"js-form-address\">
                
                <div class=\"field\">
                    <label>Город</label>
                    <input type=\"text\" name=\"city\">
                </div>
                <div class=\"field\">
                    <label>Улица</label>
                    <input type=\"text\" name=\"street\">
                </div>
                
                <div class=\"tooltip\" style=\"display: none;\"><b></b><span></span></div>
            </form>
        </div>
\t<h2>Добавить нового пользователя</h2>
\t<form role=\"form\" action=\"/phones/add/\" method=\"POST\">

\t\t<div class=\"form-group\">
\t\t\t<label for=\"last-name\">Фамилия</label>
\t\t\t<input type=\"text\" class=\"form-control\" id=\"last-name\" name=\"LastName\" placeholder=\"Фамилия\">\t\t
\t\t</div>

\t\t<div class=\"form-group\">
\t\t\t<label for=\"first-name\">Имя</label>
\t\t\t<input type=\"text\" class=\"form-control\" id=\"first-name\" name=\"FirstName\" placeholder=\"Имя\">\t\t\t
\t\t</div>

\t\t<div class=\"form-group\">
\t\t\t<label for=\"middle-name\">Отчество</label>
\t\t\t<input type=\"text\" class=\"form-control\" id=\"middle-name\" name=\"MiddleName\" placeholder=\"Отчество\">\t\t\t
\t\t</div>

\t\t<div class=\"form-group\">\t\t\t
\t\t\t<label for=\"birthday\">Дата рождения</label>
\t\t\t<input type=\"date\" class=\"form-control\" id=\"birthday\" name=\"Birthday\">\t\t\t
\t\t</div>

\t\t

\t\t<div class=\"form-group\">
\t\t\t<label for=\"home\">Дом</label>
\t\t\t<input type=\"number\" class=\"form-control\" id=\"home\" name=\"Home\" placeholder=\"Дом\">
\t\t</div>

\t\t<div class=\"form-group\">
\t\t\t<label for=\"appartment\">Квартира</label>
\t\t\t<input type=\"number\" class=\"form-control\" id=\"appartment\" name=\"Appartment\" placeholder=\"Квартира\">\t\t\t
\t\t</div>

\t\t<div class=\"form-group\">
\t\t\t<label for=\"phone\">Телефон</label>
\t\t\t<input type=\"number\" class=\"form-control\" id=\"phone\" name=\"PhoneNumber\" placeholder=\"Телефон\">
\t\t</div>

\t\t<button type=\"submit\" class=\"btn btn-primary\" name=\"submitInsertOne\">Добавить</button>


<!--
\t\t\t\t<div class=\"form-group\">
\t\t\t<label for=\"cit2y\">Гор</label>
\t\t\t<p><select name=\"hero[]\">
    <option disabled>Выберите город</option>
    ";
        // line 75
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["city"]) ? $context["city"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["cit"]) {
            // line 76
            echo "    <option value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($context["cit"], "name", array()), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute($context["cit"], "name", array()), "html", null, true);
            echo "</option>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['cit'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 78
        echo "    
   </select></p>


\t\t</div>
-->
\t</form>
</div>

";
    }

    public function getTemplateName()
    {
        return "add_phone.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  124 => 78,  113 => 76,  109 => 75,  40 => 8,  37 => 7,  32 => 4,  29 => 3,  11 => 1,);
    }
}
/* {% extends "template.html" %}*/
/* */
/* {% block title %}*/
/* Добавить нового пользователя*/
/* {% endblock %}*/
/* */
/* {% block content %}*/
/* */
/* <div class="container">*/
/* 	<a href="/" class="pull-left">Вернуться на главную</a><br>*/
/* <div class="address">*/
/*             <h1>Форма для ввода адреса</h1>*/
/*             <form class="js-form-address">*/
/*                 */
/*                 <div class="field">*/
/*                     <label>Город</label>*/
/*                     <input type="text" name="city">*/
/*                 </div>*/
/*                 <div class="field">*/
/*                     <label>Улица</label>*/
/*                     <input type="text" name="street">*/
/*                 </div>*/
/*                 */
/*                 <div class="tooltip" style="display: none;"><b></b><span></span></div>*/
/*             </form>*/
/*         </div>*/
/* 	<h2>Добавить нового пользователя</h2>*/
/* 	<form role="form" action="/phones/add/" method="POST">*/
/* */
/* 		<div class="form-group">*/
/* 			<label for="last-name">Фамилия</label>*/
/* 			<input type="text" class="form-control" id="last-name" name="LastName" placeholder="Фамилия">		*/
/* 		</div>*/
/* */
/* 		<div class="form-group">*/
/* 			<label for="first-name">Имя</label>*/
/* 			<input type="text" class="form-control" id="first-name" name="FirstName" placeholder="Имя">			*/
/* 		</div>*/
/* */
/* 		<div class="form-group">*/
/* 			<label for="middle-name">Отчество</label>*/
/* 			<input type="text" class="form-control" id="middle-name" name="MiddleName" placeholder="Отчество">			*/
/* 		</div>*/
/* */
/* 		<div class="form-group">			*/
/* 			<label for="birthday">Дата рождения</label>*/
/* 			<input type="date" class="form-control" id="birthday" name="Birthday">			*/
/* 		</div>*/
/* */
/* 		*/
/* */
/* 		<div class="form-group">*/
/* 			<label for="home">Дом</label>*/
/* 			<input type="number" class="form-control" id="home" name="Home" placeholder="Дом">*/
/* 		</div>*/
/* */
/* 		<div class="form-group">*/
/* 			<label for="appartment">Квартира</label>*/
/* 			<input type="number" class="form-control" id="appartment" name="Appartment" placeholder="Квартира">			*/
/* 		</div>*/
/* */
/* 		<div class="form-group">*/
/* 			<label for="phone">Телефон</label>*/
/* 			<input type="number" class="form-control" id="phone" name="PhoneNumber" placeholder="Телефон">*/
/* 		</div>*/
/* */
/* 		<button type="submit" class="btn btn-primary" name="submitInsertOne">Добавить</button>*/
/* */
/* */
/* <!--*/
/* 				<div class="form-group">*/
/* 			<label for="cit2y">Гор</label>*/
/* 			<p><select name="hero[]">*/
/*     <option disabled>Выберите город</option>*/
/*     {% for cit in city %}*/
/*     <option value="{{ cit.name }}">{{ cit.name }}</option>*/
/*     {% endfor %}*/
/*     */
/*    </select></p>*/
/* */
/* */
/* 		</div>*/
/* -->*/
/* 	</form>*/
/* </div>*/
/* */
/* {% endblock %}*/
