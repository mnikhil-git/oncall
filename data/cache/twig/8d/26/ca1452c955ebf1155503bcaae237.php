<?php

/* users.twig */
class __TwigTemplate_8d26ca1452c955ebf1155503bcaae237 extends Twig_Template
{
    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<html>
<body>
<h1>Users:</h1>
<table>
<tr>
\t<th>Id</th><th>Username</th><th>Password</th>
</tr>
";
        // line 8
        if ($this->getContext($context, "users")) {
            // line 9
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getContext($context, "users"));
            foreach ($context['_seq'] as $context["_key"] => $context["user"]) {
                // line 10
                echo "<tr>
\t<td>";
                // line 11
                echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "user"), "id"), "html", null, true);
                echo "</td>
\t<td>";
                // line 12
                echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "user"), "username"));
                echo "</td>
\t<td>";
                // line 13
                echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "user"), "password"));
                echo "</td>
</tr>
";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['user'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
        } else {
            // line 17
            echo "<tr><td colspan=\"3\">No Users Found...</td></tr>
";
        }
        // line 19
        echo "</table>
</body>
</html>
";
    }

    public function getTemplateName()
    {
        return "users.twig";
    }

    public function isTraitable()
    {
        return false;
    }
}
