<?php

/* single_article.html */
class __TwigTemplate_cb79a139d6833a9ab7d77c3ee9b7b8e754aaed682fe0e86a65dd77f75544c40e extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("template.html", "single_article.html", 1);
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
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["articles"]) ? $context["articles"] : null), "name", array()), "html", null, true);
        echo "
";
    }

    // line 7
    public function block_content($context, array $blocks = array())
    {
        // line 8
        echo "
<div class=\"container\">
\t<a href=\"/\" class=\"pull-left\">Вернуться на главную</a>
\t<a href=\"/users/logoff/\" class=\"pull-right\">LOGOFF</a>
\t<h1>";
        // line 12
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["articles"]) ? $context["articles"] : null), "name", array()), "html", null, true);
        echo "</h1>

\t<div class=\"article\">
\t\t<p>";
        // line 15
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["articles"]) ? $context["articles"] : null), "content", array()), "html", null, true);
        echo "</p>
\t\t<p>";
        // line 16
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["articles"]) ? $context["articles"] : null), "date", array()), "html", null, true);
        echo "</p>
\t\t<p>";
        // line 17
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["articles"]) ? $context["articles"] : null), "id", array()), "html", null, true);
        echo "</p>
\t\t<hr>

\t\t<h3>Комментарии</h3>
\t\t";
        // line 21
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($context["comment"]);
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["comment"]) {
            // line 22
            echo "\t\t<div class=\"container\">
\t\t\t<p><b>";
            // line 23
            echo twig_escape_filter($this->env, $this->getAttribute($context["comment"], "name", array()), "html", null, true);
            echo "</b></p>
\t\t\t<p>";
            // line 24
            echo twig_escape_filter($this->env, $this->getAttribute($context["comment"], "content", array()), "html", null, true);
            echo "</p>
\t\t\t<p><em>";
            // line 25
            echo twig_escape_filter($this->env, $this->getAttribute($context["comment"], "date", array()), "html", null, true);
            echo "</em></p>
\t\t</div>
\t\t";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 28
            echo "\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['comment'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 29
        echo "
\t\t";
        // line 30
        if ($this->getAttribute((isset($context["user"]) ? $context["user"] : null), "isAuth", array())) {
            // line 31
            echo "
\t\t<form action=\"/articles/addComment/\" method=\"post\">
\t\t\t<div class=\"form-group\">
\t\t\t\t<input type=\"hidden\" name='id_article' value=\"";
            // line 34
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["articles"]) ? $context["articles"] : null), "id", array()), "html", null, true);
            echo "\">
\t\t\t\t<input type=\"text\" name=\"name\">
\t\t\t\t<textarea class=\"form-control\" name=\"content\"></textarea>
\t\t\t\t<button type=\"submit\" class=\"btn btn-default\" name=\"addComment\">Отправить</button>
\t\t\t</div>
\t\t\t";
        } else {
            // line 40
            echo "\t\t\t<p>Для оставления комментариев необходимо <a href=\"/users/login/\"> зарегистрироваться </a></p>
\t\t\t";
        }
        // line 42
        echo "\t\t</form>



\t\t<form action=\"/articles/update/\" method=\"POST\">
\t\t\t<div class=\"form-group\">

\t\t\t\t<h2>Изменить статью</h2>
\t\t\t\t<input type=\"hidden\" name='id_article' value=\"";
        // line 50
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["articles"]) ? $context["articles"] : null), "id", array()), "html", null, true);
        echo "\">
\t\t\t\t<br>
\t\t\t\t<label for=\"article-name\">Заголовок</label>
\t\t\t\t<input type=\"text\" name=\"name\" size=\"100\" value=\"";
        // line 53
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["articles"]) ? $context["articles"] : null), "name", array()), "html", null, true);
        echo "\">
\t\t\t\t<br>
\t\t\t\t<br>
\t\t\t\t<label for=\"article-content\">Содержимое</label>
\t\t\t\t<textarea type=\"text\" class=\"form-control\" id=\"article-content\" name=\"content\">";
        // line 57
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["articles"]) ? $context["articles"] : null), "content", array()), "html", null, true);
        echo "</textarea><br>
\t\t\t\t<br>

\t\t\t\t<button type=\"submit\" class=\"btn btn-default\" name=\"submitChange\">Изменить запись</button>

\t\t\t</div>
\t\t</form>
\t</div>

\t";
    }

    public function getTemplateName()
    {
        return "single_article.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  144 => 57,  137 => 53,  131 => 50,  121 => 42,  117 => 40,  108 => 34,  103 => 31,  101 => 30,  98 => 29,  92 => 28,  84 => 25,  80 => 24,  76 => 23,  73 => 22,  68 => 21,  61 => 17,  57 => 16,  53 => 15,  47 => 12,  41 => 8,  38 => 7,  32 => 4,  29 => 3,  11 => 1,);
    }
}
/* {% extends "template.html" %}*/
/* */
/* {% block title %}*/
/* {{ articles.name }}*/
/* {% endblock %}*/
/* */
/* {% block content %}*/
/* */
/* <div class="container">*/
/* 	<a href="/" class="pull-left">Вернуться на главную</a>*/
/* 	<a href="/users/logoff/" class="pull-right">LOGOFF</a>*/
/* 	<h1>{{ articles.name }}</h1>*/
/* */
/* 	<div class="article">*/
/* 		<p>{{ articles.content }}</p>*/
/* 		<p>{{ articles.date }}</p>*/
/* 		<p>{{ articles.id }}</p>*/
/* 		<hr>*/
/* */
/* 		<h3>Комментарии</h3>*/
/* 		{% for comment in comment %}*/
/* 		<div class="container">*/
/* 			<p><b>{{ comment.name }}</b></p>*/
/* 			<p>{{ comment.content }}</p>*/
/* 			<p><em>{{ comment.date }}</em></p>*/
/* 		</div>*/
/* 		{% else %}*/
/* 		{% endfor %}*/
/* */
/* 		{% if user.isAuth %}*/
/* */
/* 		<form action="/articles/addComment/" method="post">*/
/* 			<div class="form-group">*/
/* 				<input type="hidden" name='id_article' value="{{ articles.id }}">*/
/* 				<input type="text" name="name">*/
/* 				<textarea class="form-control" name="content"></textarea>*/
/* 				<button type="submit" class="btn btn-default" name="addComment">Отправить</button>*/
/* 			</div>*/
/* 			{% else %}*/
/* 			<p>Для оставления комментариев необходимо <a href="/users/login/"> зарегистрироваться </a></p>*/
/* 			{% endif %}*/
/* 		</form>*/
/* */
/* */
/* */
/* 		<form action="/articles/update/" method="POST">*/
/* 			<div class="form-group">*/
/* */
/* 				<h2>Изменить статью</h2>*/
/* 				<input type="hidden" name='id_article' value="{{ articles.id }}">*/
/* 				<br>*/
/* 				<label for="article-name">Заголовок</label>*/
/* 				<input type="text" name="name" size="100" value="{{ articles.name }}">*/
/* 				<br>*/
/* 				<br>*/
/* 				<label for="article-content">Содержимое</label>*/
/* 				<textarea type="text" class="form-control" id="article-content" name="content">{{ articles.content }}</textarea><br>*/
/* 				<br>*/
/* */
/* 				<button type="submit" class="btn btn-default" name="submitChange">Изменить запись</button>*/
/* */
/* 			</div>*/
/* 		</form>*/
/* 	</div>*/
/* */
/* 	{% endblock %}*/
/* */
