<?php

/* articles_list.html */
class __TwigTemplate_27345d5296496d4446f70d545b8200dc0f10f88207fde186107f596adf168fb9 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("template.html", "articles_list.html", 1);
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

    // line 8
    public function block_content($context, array $blocks = array())
    {
        // line 9
        echo "
<div class=\"container\">

\t<form action=\"/articles/delete/\" method=\"POST\">
\t<input type=\"submit\" name=\"/articles/delete/\" value=\"Удалить выбранные статьи\">
\t\t<h1>Статьи</h1>
\t\t";
        // line 15
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["articles"]) ? $context["articles"] : null));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["article"]) {
            // line 16
            echo "\t\t<div class=\"article\">
\t\t\t<h3>\t<input type=\"checkbox\" name=\"deletelist[]\" value=\"";
            // line 17
            echo twig_escape_filter($this->env, $this->getAttribute($context["article"], "id", array()), "html", null, true);
            echo "\"><a href=\"/articles/show/";
            echo twig_escape_filter($this->env, $this->getAttribute($context["article"], "id", array()), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute($context["article"], "name", array()), "html", null, true);
            echo "</a></h3>
\t\t
\t\t\t<p>";
            // line 19
            echo twig_escape_filter($this->env, $this->getAttribute($context["article"], "content", array()), "html", null, true);
            echo "</p>
\t\t\t<p>id: ";
            // line 20
            echo twig_escape_filter($this->env, $this->getAttribute($context["article"], "id", array()), "html", null, true);
            echo "</p>
\t\t\t<hr>
\t\t</div>
\t\t";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 24
            echo "\t\t<p>Нет статей</p>
\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['article'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 26
        echo "\t</form>
\t<form action=\"/articles/add/\" method=\"POST\">
\t\t<fieldset>
\t\t\t<legend>Добавить одну запись</legend>
\t\t\t<input type=\"text\" name=\"name\" size=\"150\" placeholder=\"Введите название статьи\" required><br>
\t\t\t<br>
\t\t\t<textarea name=\"content\" cols=\"150\" rows=\"20\" placeholder=\"Введите текст статьи\" required>
\t\t\t</textarea><br>
\t\t\t<br>
\t\t\t<input type=\"submit\" name=\"submitInsertOne\" value=\"Добавить запись\">
\t\t</fieldset>
\t</form>

\t<br><br>

\t
</div>
";
    }

    public function getTemplateName()
    {
        return "articles_list.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  86 => 26,  79 => 24,  70 => 20,  66 => 19,  57 => 17,  54 => 16,  49 => 15,  41 => 9,  38 => 8,  32 => 4,  29 => 3,  11 => 1,);
    }
}
/* {% extends "template.html" %}*/
/* */
/* {% block title %}*/
/* {{ title }}*/
/* {% endblock %}*/
/* */
/* */
/* {% block content %}*/
/* */
/* <div class="container">*/
/* */
/* 	<form action="/articles/delete/" method="POST">*/
/* 	<input type="submit" name="/articles/delete/" value="Удалить выбранные статьи">*/
/* 		<h1>Статьи</h1>*/
/* 		{% for article in articles %}*/
/* 		<div class="article">*/
/* 			<h3>	<input type="checkbox" name="deletelist[]" value="{{ article.id }}"><a href="/articles/show/{{ article.id }}">{{ article.name }}</a></h3>*/
/* 		*/
/* 			<p>{{ article.content }}</p>*/
/* 			<p>id: {{ article.id }}</p>*/
/* 			<hr>*/
/* 		</div>*/
/* 		{% else %}*/
/* 		<p>Нет статей</p>*/
/* 		{% endfor %}*/
/* 	</form>*/
/* 	<form action="/articles/add/" method="POST">*/
/* 		<fieldset>*/
/* 			<legend>Добавить одну запись</legend>*/
/* 			<input type="text" name="name" size="150" placeholder="Введите название статьи" required><br>*/
/* 			<br>*/
/* 			<textarea name="content" cols="150" rows="20" placeholder="Введите текст статьи" required>*/
/* 			</textarea><br>*/
/* 			<br>*/
/* 			<input type="submit" name="submitInsertOne" value="Добавить запись">*/
/* 		</fieldset>*/
/* 	</form>*/
/* */
/* 	<br><br>*/
/* */
/* 	*/
/* </div>*/
/* {% endblock %}*/
