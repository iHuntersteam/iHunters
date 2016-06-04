<?php

/* dailyStatistics.html */
class __TwigTemplate_df60d30bee0d58aebde52768bbb681a76166b3a1cf32024e18bc2c56a16f77a0 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("template.html", "dailyStatistics.html", 1);
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
        echo "\t";
        echo twig_escape_filter($this->env, (isset($context["title"]) ? $context["title"] : null), "html", null, true);
        echo "
";
    }

    // line 7
    public function block_content($context, array $blocks = array())
    {
        // line 8
        echo "
\t<div class=\"container\" style=\"margin-top:30px\">

\t\t<div class=\"row\">
\t\t\t<div class=\"col-md-3\">

\t\t\t\t<ul class=\"unstyled\" style=\"list-style-type: none\">
\t\t\t\t\t<a href=\"/stats/showCommonStatistics/\"><li>Общая статистика</li></a>
\t\t\t\t\t<a href=\"/stats/showDailyStatistics/\"><li><b>Ежедневная статистика</b></li></a>\t
\t\t\t\t</ul>

\t\t\t</div>
\t\t\t<div class=\"col-md-7\">
\t\t\t\t<form action=\"/stats/showStatisticsPerDay/\" method=\"POST\">
\t\t\t\t\t<p class=\"col-md-2\">Сайт:</p>

\t\t\t\t\t<select name=\"siteId\" id=\"sites\" class=\"col-md-4 pull-left\">
\t\t\t\t\t\t";
        // line 25
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["sites"]) ? $context["sites"] : null));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["site"]) {
            // line 26
            echo "\t\t\t\t\t\t\t<option value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($context["site"], "id", array()), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute($context["site"], "name", array()), "html", null, true);
            echo "</option>
\t\t\t\t\t\t\t";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 28
            echo "\t\t\t\t\t\t\t<p>Нет сайтов</p>
\t\t\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['site'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 29
        echo "  
\t\t\t\t\t</select>

\t\t\t\t\t<br><br>
\t\t\t\t\t<p class=\"col-md-2\">Личность:</p>

\t\t\t\t\t<select name=\"personId\" class=\"col-md-4 pull-left\">
\t\t\t\t\t\t";
        // line 36
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["persons"]) ? $context["persons"] : null));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["person"]) {
            // line 37
            echo "\t\t\t\t\t\t\t<option value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($context["person"], "id", array()), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute($context["person"], "name", array()), "html", null, true);
            echo "</option>
\t\t\t\t\t\t\t";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 39
            echo "\t\t\t\t\t\t\t<p>Нет личностей</p>
\t\t\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['person'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 40
        echo "  
\t\t\t\t\t</select>

\t\t\t\t\t<br><br>

\t\t\t\t\t<p class=\"col-md-2\">Период с:</p>
\t\t\t\t\t<input type=\"date\" name=\"beginDate\" value=\"2016-05-27\"> 
\t\t\t\t\tпо <input type=\"date\" name=\"endDate\" value=\"2016-05-30\"> 

\t\t\t\t\t<button type=\"submit\" class=\"btn btn-primary pull-right\">Применить</button>

\t\t\t\t</form>
\t\t\t\t<table class = \"table\">
\t\t\t\t\t<thead>

\t\t\t\t\t\t<tr>
\t\t\t\t\t\t\t<th>Дата</th>
\t\t\t\t\t\t\t<th>Количество новых страниц</th>
\t\t\t\t\t\t</tr>

\t\t\t\t\t</thead>
\t\t\t\t\t<tbody>
\t\t\t\t\t\t";
        // line 62
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["daily"]) ? $context["daily"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["dail"]) {
            // line 63
            echo "\t\t\t\t\t\t\t<tr>
\t\t\t\t\t\t\t\t<td>";
            // line 64
            echo twig_escape_filter($this->env, $this->getAttribute($context["dail"], "last_scan_date", array()), "html", null, true);
            echo "</td>
\t\t\t\t\t\t\t\t<td>";
            // line 65
            echo twig_escape_filter($this->env, $this->getAttribute($context["dail"], "rank", array()), "html", null, true);
            echo " </td>
\t\t\t\t\t\t\t</tr>
\t\t\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['dail'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 67
        echo " 
\t\t\t\t\t</tbody>
\t\t\t\t\t<tfoot>
    <tr>
      <td><b>Всего за период:</b></td>
      <td>180</td>
    </tr>
  </tfoot>
\t\t\t\t</table>
\t\t\t</div>
\t\t</div>
\t</div>
\t
\t";
    }

    public function getTemplateName()
    {
        return "dailyStatistics.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  158 => 67,  149 => 65,  145 => 64,  142 => 63,  138 => 62,  114 => 40,  107 => 39,  97 => 37,  92 => 36,  83 => 29,  76 => 28,  66 => 26,  61 => 25,  42 => 8,  39 => 7,  32 => 4,  29 => 3,  11 => 1,);
    }
}
/* {% extends "template.html" %}*/
/* */
/* {% block title %}*/
/* 	{{ title }}*/
/* {% endblock %}*/
/* */
/* {% block content %}*/
/* */
/* 	<div class="container" style="margin-top:30px">*/
/* */
/* 		<div class="row">*/
/* 			<div class="col-md-3">*/
/* */
/* 				<ul class="unstyled" style="list-style-type: none">*/
/* 					<a href="/stats/showCommonStatistics/"><li>Общая статистика</li></a>*/
/* 					<a href="/stats/showDailyStatistics/"><li><b>Ежедневная статистика</b></li></a>	*/
/* 				</ul>*/
/* */
/* 			</div>*/
/* 			<div class="col-md-7">*/
/* 				<form action="/stats/showStatisticsPerDay/" method="POST">*/
/* 					<p class="col-md-2">Сайт:</p>*/
/* */
/* 					<select name="siteId" id="sites" class="col-md-4 pull-left">*/
/* 						{% for site in sites %}*/
/* 							<option value="{{ site.id }}">{{ site.name }}</option>*/
/* 							{% else %}*/
/* 							<p>Нет сайтов</p>*/
/* 						{% endfor %}  */
/* 					</select>*/
/* */
/* 					<br><br>*/
/* 					<p class="col-md-2">Личность:</p>*/
/* */
/* 					<select name="personId" class="col-md-4 pull-left">*/
/* 						{% for person in persons %}*/
/* 							<option value="{{ person.id }}">{{ person.name }}</option>*/
/* 							{% else %}*/
/* 							<p>Нет личностей</p>*/
/* 						{% endfor %}  */
/* 					</select>*/
/* */
/* 					<br><br>*/
/* */
/* 					<p class="col-md-2">Период с:</p>*/
/* 					<input type="date" name="beginDate" value="2016-05-27"> */
/* 					по <input type="date" name="endDate" value="2016-05-30"> */
/* */
/* 					<button type="submit" class="btn btn-primary pull-right">Применить</button>*/
/* */
/* 				</form>*/
/* 				<table class = "table">*/
/* 					<thead>*/
/* */
/* 						<tr>*/
/* 							<th>Дата</th>*/
/* 							<th>Количество новых страниц</th>*/
/* 						</tr>*/
/* */
/* 					</thead>*/
/* 					<tbody>*/
/* 						{% for dail in daily %}*/
/* 							<tr>*/
/* 								<td>{{ dail.last_scan_date }}</td>*/
/* 								<td>{{ dail.rank }} </td>*/
/* 							</tr>*/
/* 						{% endfor %} */
/* 					</tbody>*/
/* 					<tfoot>*/
/*     <tr>*/
/*       <td><b>Всего за период:</b></td>*/
/*       <td>180</td>*/
/*     </tr>*/
/*   </tfoot>*/
/* 				</table>*/
/* 			</div>*/
/* 		</div>*/
/* 	</div>*/
/* 	*/
/* 	{% endblock %}*/
