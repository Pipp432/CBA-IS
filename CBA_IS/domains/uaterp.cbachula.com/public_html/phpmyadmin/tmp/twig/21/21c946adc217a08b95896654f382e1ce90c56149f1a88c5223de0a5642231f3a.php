<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* server/plugins/index.twig */
class __TwigTemplate_383cbeebd8964010a41f9bbd7dcd89536a28c94149473e3649ca3330c0eb543c extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<div class=\"container-fluid\">
<h2>
  ";
        // line 3
        echo \PhpMyAdmin\Html\Generator::getImage("b_plugin");
        echo "
  ";
        // line 4
        echo _gettext("Plugins");
        // line 5
        echo "</h2>

<div id=\"plugins_plugins\">
  <div id=\"sectionlinks\" class=\"row no-gutters\">
    <div class=\"col-12\">
      ";
        // line 10
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(twig_get_array_keys_filter(($context["plugins"] ?? null)));
        foreach ($context['_seq'] as $context["_key"] => $context["type"]) {
            // line 11
            echo "        <a class=\"btn btn-primary\" href=\"#plugins-";
            echo twig_escape_filter($this->env, (($__internal_compile_0 = ($context["clean_types"] ?? null)) && is_array($__internal_compile_0) || $__internal_compile_0 instanceof ArrayAccess ? ($__internal_compile_0[$context["type"]] ?? null) : null), "html", null, true);
            echo "\">
          ";
            // line 12
            echo twig_escape_filter($this->env, $context["type"], "html", null, true);
            echo "
        </a>
      ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['type'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 15
        echo "    </div>
  </div>
  ";
        // line 17
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["plugins"] ?? null));
        foreach ($context['_seq'] as $context["type"] => $context["list"]) {
            // line 18
            echo "    <div class=\"row\">
      <div class=\"table-responsive col-12\">
        <table class=\"table table-light table-striped table-hover w-auto\" id=\"plugins-";
            // line 20
            echo twig_escape_filter($this->env, (($__internal_compile_1 = ($context["clean_types"] ?? null)) && is_array($__internal_compile_1) || $__internal_compile_1 instanceof ArrayAccess ? ($__internal_compile_1[$context["type"]] ?? null) : null), "html", null, true);
            echo "\">
          <caption>
            ";
            // line 22
            echo twig_escape_filter($this->env, $context["type"], "html", null, true);
            echo "
          </caption>
          <thead class=\"thead-light\">
            <tr>
              <th scope=\"col\">";
            // line 26
            echo _gettext("Plugin");
            echo "</th>
              <th scope=\"col\">";
            // line 27
            echo _gettext("Description");
            echo "</th>
              <th scope=\"col\">";
            // line 28
            echo _gettext("Version");
            echo "</th>
              <th scope=\"col\">";
            // line 29
            echo _gettext("Author");
            echo "</th>
              <th scope=\"col\">";
            // line 30
            echo _gettext("License");
            echo "</th>
            </tr>
          </thead>
          <tbody>
            ";
            // line 34
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($context["list"]);
            foreach ($context['_seq'] as $context["_key"] => $context["plugin"]) {
                // line 35
                echo "              <tr class=\"noclick\">
                <th>
                  ";
                // line 37
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["plugin"], "name", [], "any", false, false, false, 37), "html", null, true);
                echo "
                  ";
                // line 38
                if ((twig_get_attribute($this->env, $this->source, $context["plugin"], "status", [], "any", false, false, false, 38) != "ACTIVE")) {
                    // line 39
                    echo "                    <span class=\"badge badge-danger\">
                      ";
                    // line 40
                    if ((twig_get_attribute($this->env, $this->source, $context["plugin"], "status", [], "any", false, false, false, 40) == "INACTIVE")) {
                        // line 41
                        echo "                        ";
                        echo _gettext("inactive");
                        // line 42
                        echo "                      ";
                    } elseif ((twig_get_attribute($this->env, $this->source, $context["plugin"], "status", [], "any", false, false, false, 42) == "DISABLED")) {
                        // line 43
                        echo "                        ";
                        echo _gettext("disabled");
                        // line 44
                        echo "                      ";
                    } elseif ((twig_get_attribute($this->env, $this->source, $context["plugin"], "status", [], "any", false, false, false, 44) == "DELETING")) {
                        // line 45
                        echo "                        ";
                        echo _gettext("deleting");
                        // line 46
                        echo "                      ";
                    } elseif ((twig_get_attribute($this->env, $this->source, $context["plugin"], "status", [], "any", false, false, false, 46) == "DELETED")) {
                        // line 47
                        echo "                        ";
                        echo _gettext("deleted");
                        // line 48
                        echo "                      ";
                    }
                    // line 49
                    echo "                    </span>
                  ";
                }
                // line 51
                echo "                </th>
                <td>";
                // line 52
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["plugin"], "description", [], "any", false, false, false, 52), "html", null, true);
                echo "</td>
                <td>";
                // line 53
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["plugin"], "version", [], "any", false, false, false, 53), "html", null, true);
                echo "</td>
                <td>";
                // line 54
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["plugin"], "author", [], "any", false, false, false, 54), "html", null, true);
                echo "</td>
                <td>";
                // line 55
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["plugin"], "license", [], "any", false, false, false, 55), "html", null, true);
                echo "</td>
              </tr>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['plugin'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 58
            echo "          </tbody>
        </table>
      </div>
    </div>
  ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['type'], $context['list'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 63
        echo "</div>
</div>
";
    }

    public function getTemplateName()
    {
        return "server/plugins/index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  200 => 63,  190 => 58,  181 => 55,  177 => 54,  173 => 53,  169 => 52,  166 => 51,  162 => 49,  159 => 48,  156 => 47,  153 => 46,  150 => 45,  147 => 44,  144 => 43,  141 => 42,  138 => 41,  136 => 40,  133 => 39,  131 => 38,  127 => 37,  123 => 35,  119 => 34,  112 => 30,  108 => 29,  104 => 28,  100 => 27,  96 => 26,  89 => 22,  84 => 20,  80 => 18,  76 => 17,  72 => 15,  63 => 12,  58 => 11,  54 => 10,  47 => 5,  45 => 4,  41 => 3,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "server/plugins/index.twig", "/home/erp/domains/uaterp.cbachula.com/public_html/phpmyadmin/templates/server/plugins/index.twig");
    }
}
