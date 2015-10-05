<?php

/* index.html */
class __TwigTemplate_e6fcbffe30f56188a6be20abba14db7abfdb6de5c0517b070f1a3245f15ef6df extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html>
\t<head>
\t\t<title>";
        // line 4
        echo twig_escape_filter($this->env, (isset($context["title"]) ? $context["title"] : null), "html", null, true);
        echo "</title>
\t\t<link type=\"text/css\" href=\"./css/style.css\" rel=\"stylesheet\" />
\t\t<script type=\"text/javascript\" src=\"./vendor/components/jquery/jquery.min.js\"></script>
\t\t<script type=\"text/javascript\" src=\"./js/game.js\"></script>
\t</head>
\t<body>
\t\t<div class=\"container\">
\t\t\t<img class=\"light-left\" src=\"./images/light.png\" />
\t\t\t<div id=\"screen\" style=\"background-image: url('images/";
        // line 12
        echo twig_escape_filter($this->env, (isset($context["imageUrl"]) ? $context["imageUrl"] : null), "html", null, true);
        echo "');\"></div>
\t\t\t";
        // line 13
        if ( !(isset($context["isExiting"]) ? $context["isExiting"] : null)) {
            // line 14
            echo "\t\t\t\t<div class=\"form-container\">
\t\t\t\t\t<form method=\"post\" id=\"answerForm\" name=\"answerForm\">
\t\t\t\t\t\t<input type=\"hidden\" id=\"commandLine\" name=\"commandLine\" value=\"\" />
\t\t\t\t\t\t<textarea autofocus id=\"commandHistory\" spellcheck=\"false\" autocorrect=\"false\" autocapitalize=\"false\">";
            // line 17
            echo twig_escape_filter($this->env, (isset($context["consoleHistory"]) ? $context["consoleHistory"] : null), "html", null, true);
            echo twig_escape_filter($this->env, (isset($context["eol"]) ? $context["eol"] : null), "html", null, true);
            echo twig_escape_filter($this->env, (isset($context["prompt"]) ? $context["prompt"] : null), "html", null, true);
            echo "</textarea>
\t\t\t\t\t\t<input id=\"button\" hidden type=\"submit\" value=\"Enter Command\" />
\t\t\t\t\t</form>
\t\t\t\t</div>
\t\t\t";
        }
        // line 22
        echo "\t\t\t<img class=\"light-right\" src=\"./images/light.png\" />
\t\t</div>
\t</body>
</html>
";
    }

    public function getTemplateName()
    {
        return "index.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  56 => 22,  46 => 17,  41 => 14,  39 => 13,  35 => 12,  24 => 4,  19 => 1,);
    }
}
/* <!DOCTYPE html>*/
/* <html>*/
/* 	<head>*/
/* 		<title>{{title}}</title>*/
/* 		<link type="text/css" href="./css/style.css" rel="stylesheet" />*/
/* 		<script type="text/javascript" src="./vendor/components/jquery/jquery.min.js"></script>*/
/* 		<script type="text/javascript" src="./js/game.js"></script>*/
/* 	</head>*/
/* 	<body>*/
/* 		<div class="container">*/
/* 			<img class="light-left" src="./images/light.png" />*/
/* 			<div id="screen" style="background-image: url('images/{{imageUrl}}');"></div>*/
/* 			{% if not isExiting %}*/
/* 				<div class="form-container">*/
/* 					<form method="post" id="answerForm" name="answerForm">*/
/* 						<input type="hidden" id="commandLine" name="commandLine" value="" />*/
/* 						<textarea autofocus id="commandHistory" spellcheck="false" autocorrect="false" autocapitalize="false">{{consoleHistory}}{{eol}}{{prompt}}</textarea>*/
/* 						<input id="button" hidden type="submit" value="Enter Command" />*/
/* 					</form>*/
/* 				</div>*/
/* 			{% endif %}*/
/* 			<img class="light-right" src="./images/light.png" />*/
/* 		</div>*/
/* 	</body>*/
/* </html>*/
/* */
