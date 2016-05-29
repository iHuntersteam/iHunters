<?php

/* phonebook.html */
class __TwigTemplate_e8a71bd2e9ae99206f94e56b5b7fdf5fbf4a5e9da952aab925775fad72cf656e extends Twig_Template
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

\t<div class=\"row\">
\t\t<div class=\"col-md-3\">

\t\t\t<ul class=\"unstyled\" style=\"list-style-type: none\">
\t\t\t\t<a href=\"\"><li>Общая статистика</li></a>
\t\t\t\t<a href=\"\"><li>Ежедневная статистика</li></a>\t
\t\t\t</ul>

\t\t</div>
\t\t<div class=\"col-md-4\">

\t\t\t<label for=\"sites\">Сайт:&nbsp&nbsp&nbsp</label>
\t\t\t
\t\t\t<select id=\"sites\">
\t\t\t\t<option>lenta.ru</option>
\t\t\t\t<option>mail.ru</option>
\t\t\t\t<option>yandex.ru</option>
\t\t\t\t<option>vedomosti.ru</option>  
\t\t\t</select>

\t\t\t<a href='/phones/delete/";
        // line 31
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["phone"]) ? $context["phone"] : null), "id", array()), "html", null, true);
        echo "' class=\"btn btn-primary pull-right\">Применить</a>

\t\t\t<table class = \"table\">
\t\t\t\t<thead>
\t\t\t\t\t<tr>
\t\t\t\t\t\t<th>Имя</th>
\t\t\t\t\t\t<th>Количество упоминаний</th>
\t\t\t\t\t</tr>
\t\t\t\t</thead>
\t\t\t\t<tbody>
\t\t\t\t\t<tr>
\t\t\t\t\t\t<td>Путин</td>
\t\t\t\t\t\t<td>100500</td>
\t\t\t\t\t</tr>
\t\t\t\t\t<tr>
\t\t\t\t\t\t<td>Медведев</td>
\t\t\t\t\t\t<td>50000</td>
\t\t\t\t\t</tr>
\t\t\t\t</tbody>
\t\t\t</table>
\t\t</table>
\t</div>
</div>






<a href=\"/phones/addc\" class=\"btn btn-primary\">Добавить</a>
<h1>Телефонный справочник</h1>
";
        // line 62
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["phones"]) ? $context["phones"] : null));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["phone"]) {
            // line 63
            echo "<div class=\"article\">
\t<p><b>ФИО:</b> ";
            // line 64
            echo twig_escape_filter($this->env, $this->getAttribute($context["phone"], "LastName", array()), "html", null, true);
            echo " ";
            echo twig_escape_filter($this->env, $this->getAttribute($context["phone"], "FirstName", array()), "html", null, true);
            echo " ";
            echo twig_escape_filter($this->env, $this->getAttribute($context["phone"], "MiddleName", array()), "html", null, true);
            echo "</p>
\t<p><b>Дата рождения:</b> ";
            // line 65
            echo twig_escape_filter($this->env, $this->getAttribute($context["phone"], "Birthday", array()), "html", null, true);
            echo "</p>
\t<p><b>Адрес:</b> ";
            // line 66
            echo twig_escape_filter($this->env, $this->getAttribute($context["phone"], "City", array()), "html", null, true);
            echo ", ул. ";
            echo twig_escape_filter($this->env, $this->getAttribute($context["phone"], "Street", array()), "html", null, true);
            echo ", д. ";
            echo twig_escape_filter($this->env, $this->getAttribute($context["phone"], "Home", array()), "html", null, true);
            echo ", кв. ";
            echo twig_escape_filter($this->env, $this->getAttribute($context["phone"], "Appartment", array()), "html", null, true);
            echo "</p>
\t<p><b>Телефон:</b> ";
            // line 67
            echo twig_escape_filter($this->env, $this->getAttribute($context["phone"], "PhoneNumber", array()), "html", null, true);
            echo "</p>
\t<a href=\"/phones/show/";
            // line 68
            echo twig_escape_filter($this->env, $this->getAttribute($context["phone"], "id", array()), "html", null, true);
            echo "\" class=\"btn btn-default\">Редактировать</a>
\t<a href='/phones/delete/";
            // line 69
            echo twig_escape_filter($this->env, $this->getAttribute($context["phone"], "id", array()), "html", null, true);
            echo "' class=\"btn btn-danger pull-right\">Удалить</a>
\t<hr>
</div>

";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 74
            echo "<p>Нет номеров</p>
";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['phone'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 76
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
        return array (  155 => 76,  148 => 74,  138 => 69,  134 => 68,  130 => 67,  120 => 66,  116 => 65,  108 => 64,  105 => 63,  100 => 62,  66 => 31,  41 => 8,  38 => 7,  32 => 4,  29 => 3,  11 => 1,);
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
/* */
/* 	<div class="row">*/
/* 		<div class="col-md-3">*/
/* */
/* 			<ul class="unstyled" style="list-style-type: none">*/
/* 				<a href=""><li>Общая статистика</li></a>*/
/* 				<a href=""><li>Ежедневная статистика</li></a>	*/
/* 			</ul>*/
/* */
/* 		</div>*/
/* 		<div class="col-md-4">*/
/* */
/* 			<label for="sites">Сайт:&nbsp&nbsp&nbsp</label>*/
/* 			*/
/* 			<select id="sites">*/
/* 				<option>lenta.ru</option>*/
/* 				<option>mail.ru</option>*/
/* 				<option>yandex.ru</option>*/
/* 				<option>vedomosti.ru</option>  */
/* 			</select>*/
/* */
/* 			<a href='/phones/delete/{{ phone.id }}' class="btn btn-primary pull-right">Применить</a>*/
/* */
/* 			<table class = "table">*/
/* 				<thead>*/
/* 					<tr>*/
/* 						<th>Имя</th>*/
/* 						<th>Количество упоминаний</th>*/
/* 					</tr>*/
/* 				</thead>*/
/* 				<tbody>*/
/* 					<tr>*/
/* 						<td>Путин</td>*/
/* 						<td>100500</td>*/
/* 					</tr>*/
/* 					<tr>*/
/* 						<td>Медведев</td>*/
/* 						<td>50000</td>*/
/* 					</tr>*/
/* 				</tbody>*/
/* 			</table>*/
/* 		</table>*/
/* 	</div>*/
/* </div>*/
/* */
/* */
/* */
/* */
/* */
/* */
/* <a href="/phones/addc" class="btn btn-primary">Добавить</a>*/
/* <h1>Телефонный справочник</h1>*/
/* {% for phone in phones %}*/
/* <div class="article">*/
/* 	<p><b>ФИО:</b> {{ phone.LastName }} {{ phone.FirstName }} {{ phone.MiddleName }}</p>*/
/* 	<p><b>Дата рождения:</b> {{ phone.Birthday }}</p>*/
/* 	<p><b>Адрес:</b> {{ phone.City }}, ул. {{ phone.Street }}, д. {{ phone.Home }}, кв. {{ phone.Appartment }}</p>*/
/* 	<p><b>Телефон:</b> {{ phone.PhoneNumber }}</p>*/
/* 	<a href="/phones/show/{{ phone.id }}" class="btn btn-default">Редактировать</a>*/
/* 	<a href='/phones/delete/{{ phone.id }}' class="btn btn-danger pull-right">Удалить</a>*/
/* 	<hr>*/
/* </div>*/
/* */
/* {% else %}*/
/* <p>Нет номеров</p>*/
/* {% endfor %}*/
/* </div>*/
/* {% endblock %}*/
