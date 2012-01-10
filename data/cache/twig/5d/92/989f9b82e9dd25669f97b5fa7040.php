<?php

/* hello.twig */
class __TwigTemplate_5d92989f9b82e9dd25669f97b5fa7040 extends Twig_Template
{
    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<html>
<body>
<h1>Hello, ";
        // line 3
        echo twig_escape_filter($this->env, $this->getContext($context, "name"), "html", null, true);
        echo "</h1>
</body>
</html>
";
    }

    public function getTemplateName()
    {
        return "hello.twig";
    }

    public function isTraitable()
    {
        return false;
    }
}
