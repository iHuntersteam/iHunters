<?php

/* commonStatistics.html */
class __TwigTemplate_6f1ac155ec06fcf80a5ee9e1668500111eeca9329afe8dbf3229aba598482ae6 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("template.html", "commonStatistics.html", 1);
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
<div class=\"container\" style=\"margin-top:30px\">

\t<div class=\"row\">
\t\t<div class=\"col-md-3\">

\t\t\t<ul class=\"unstyled\" style=\"list-style-type: none\">
\t\t\t\t<a href=\"\"><li><b>Общая статистика</b></li></a>
\t\t\t\t<a href=\"/stats/showDailyStatistics/\"><li>Ежедневная статистика</li></a>\t
\t\t\t</ul>

\t\t</div>
\t\t<div class=\"col-md-4\">
\t\t\t<form action=\"/stats/showAll/\" method=\"POST\">
\t\t\t\t<label for=\"sites\">Сайт:&nbsp&nbsp&nbsp</label>
\t\t\t\t
\t\t\t\t<select name=\"siteId\" id=\"sites\">
\t\t\t\t\t";
        // line 25
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["sites"]) ? $context["sites"] : null));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["site"]) {
            // line 26
            echo "\t\t\t\t\t<option value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($context["site"], "id", array()), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute($context["site"], "name", array()), "html", null, true);
            echo "</option>
\t\t\t\t\t";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 28
            echo "\t\t\t\t\t<p>Нет сайтов</p>
\t\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['site'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 29
        echo "  
\t\t\t\t</select>
\t\t\t\t<button type=\"submit\" class=\"btn btn-primary pull-right\">Применить</button>
\t\t\t\t
\t\t\t</form>
\t\t\t
\t\t\t<table class = \"table\">
\t\t\t\t<thead>
\t\t\t\t\t<tr>
\t\t\t\t\t\t<th>Дата: ";
        // line 38
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["lastScanDates"]) ? $context["lastScanDates"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["lastScanDate"]) {
            // line 39
            echo "\t\t\t\t\t\t\t";
            echo twig_escape_filter($this->env, $this->getAttribute($context["lastScanDate"], "LastScanDate", array()), "html", null, true);
            echo "
\t\t\t\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['lastScanDate'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 40
        echo " </th>\t\t\t\t\t\t
\t\t\t\t\t\t</tr>
\t\t\t\t\t\t<tr>
\t\t\t\t\t\t\t<th>Имя</th>
\t\t\t\t\t\t\t<th>Количество упоминаний</th>
\t\t\t\t\t\t</tr>
\t\t\t\t\t\t
\t\t\t\t\t</thead>
\t\t\t\t\t<tbody>
\t\t\t\t\t\t";
        // line 49
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["stats"]) ? $context["stats"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["stat"]) {
            // line 50
            echo "\t\t\t\t\t\t<tr>
\t\t\t\t\t\t\t<td>";
            // line 51
            echo twig_escape_filter($this->env, $this->getAttribute($context["stat"], "name", array()), "html", null, true);
            echo "</td>
\t\t\t\t\t\t\t<td>";
            // line 52
            echo twig_escape_filter($this->env, $this->getAttribute($context["stat"], "Qty", array()), "html", null, true);
            echo " </td>
\t\t\t\t\t\t</tr>
\t\t\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['stat'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 54
        echo " 

\t\t\t\t\t</tbody>
\t\t\t\t</table>
\t\t\t</table>
\t\t</div>
\t</div>
\t";
    }

    public function getTemplateName()
    {
        return "commonStatistics.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  137 => 54,  128 => 52,  124 => 51,  121 => 50,  117 => 49,  106 => 40,  97 => 39,  93 => 38,  82 => 29,  75 => 28,  65 => 26,  60 => 25,  41 => 8,  38 => 7,  32 => 4,  29 => 3,  11 => 1,);
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
/* <div class="container" style="margin-top:30px">*/
/* */
/* 	<div class="row">*/
/* 		<div class="col-md-3">*/
/* */
/* 			<ul class="unstyled" style="list-style-type: none">*/
/* 				<a href=""><li><b>Общая статистика</b></li></a>*/
/* 				<a href="/stats/showDailyStatistics/"><li>Ежедневная статистика</li></a>	*/
/* 			</ul>*/
/* */
/* 		</div>*/
/* 		<div class="col-md-4">*/
/* 			<form action="/stats/showAll/" method="POST">*/
/* 				<label for="sites">Сайт:&nbsp&nbsp&nbsp</label>*/
/* 				*/
/* 				<select name="siteId" id="sites">*/
/* 					{% for site in sites %}*/
/* 					<option value="{{ site.id }}">{{ site.name }}</option>*/
/* 					{% else %}*/
/* 					<p>Нет сайтов</p>*/
/* 					{% endfor %}  */
/* 				</select>*/
/* 				<button type="submit" class="btn btn-primary pull-right">Применить</button>*/
/* 				*/
/* 			</form>*/
/* 			*/
/* 			<table class = "table">*/
/* 				<thead>*/
/* 					<tr>*/
/* 						<th>Дата: {% for lastScanDate in lastScanDates %}*/
/* 							{{ lastScanDate.LastScanDate }}*/
/* 							{% endfor %} </th>						*/
/* 						</tr>*/
/* 						<tr>*/
/* 							<th>Имя</th>*/
/* 							<th>Количество упоминаний</th>*/
/* 						</tr>*/
/* 						*/
/* 					</thead>*/
/* 					<tbody>*/
/* 						{% for stat in stats %}*/
/* 						<tr>*/
/* 							<td>{{ stat.name }}</td>*/
/* 							<td>{{ stat.Qty }} </td>*/
/* 						</tr>*/
/* 						{% endfor %} */
/* */
/* 					</tbody>*/
/* 				</table>*/
/* 			</table>*/
/* 		</div>*/
/* 	</div>*/
/* 	{% endblock %}*/
