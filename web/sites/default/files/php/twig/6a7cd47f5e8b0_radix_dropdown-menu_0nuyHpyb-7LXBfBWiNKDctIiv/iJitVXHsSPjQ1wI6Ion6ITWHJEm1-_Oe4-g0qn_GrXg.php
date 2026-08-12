<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Sandbox\SecurityNotAllowedTestError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* radix:dropdown-menu */
class __TwigTemplate_05092fb95cfc71d2cd02442d0bfbe1e2 extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->extensions[SandboxExtension::class];
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 1
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\TwigExtension']->attachLibrary("core/components.radix--dropdown-menu"));
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\ComponentsTwigExtension']->addAdditionalContext($context, "radix:dropdown-menu"));
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\ComponentsTwigExtension']->validateProps($context, "radix:dropdown-menu"));
        // line 7
        $context["dropdown_direction"] = (((array_key_exists("dropdown_direction", $context) &&  !(null === $context["dropdown_direction"]))) ? ($context["dropdown_direction"]) : (""));
        // line 9
        $context["dropdown_menu_classes"] = Twig\Extension\CoreExtension::merge(["dropdown-menu"], (((($tmp =         // line 11
($context["dropdown_menu_utility_classes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["dropdown_menu_utility_classes"]) : ([])));
        // line 13
        yield "
";
        // line 14
        if ((Twig\Extension\CoreExtension::length($this->env->getCharset(), ($context["items"] ?? null)) > 0)) {
            // line 15
            yield "  <ul class=\"";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, Twig\Extension\CoreExtension::join(($context["dropdown_menu_classes"] ?? null), " "), "html", null, true);
            yield "\">
    ";
            // line 16
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["items"] ?? null));
            $context['loop'] = [
              'parent' => $context['_parent'],
              'index0' => 0,
              'index'  => 1,
              'first'  => true,
            ];
            if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
                $length = count($context['_seq']);
                $context['loop']['revindex0'] = $length - 1;
                $context['loop']['revindex'] = $length;
                $context['loop']['length'] = $length;
                $context['loop']['last'] = 1 === $length;
            }
            foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                // line 17
                yield "      ";
                $context["nav_link_classes"] = ["dropdown-item", (((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["item"], "in_active_trail", [], "any", false, false, true, 17)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("active") : (""))];
                // line 18
                yield "
      ";
                // line 19
                if (is_iterable(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["item"], "url", [], "any", false, false, true, 19), "options", [], "any", false, false, true, 19), "attributes", [], "any", false, false, true, 19), "class", [], "any", false, false, true, 19))) {
                    // line 20
                    yield "        ";
                    $context["nav_link_classes"] = Twig\Extension\CoreExtension::merge(($context["nav_link_classes"] ?? null), CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["item"], "url", [], "any", false, false, true, 20), "options", [], "any", false, false, true, 20), "attributes", [], "any", false, false, true, 20), "class", [], "any", false, false, true, 20));
                    // line 21
                    yield "      ";
                } elseif ((($tmp = CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["item"], "url", [], "any", false, false, true, 21), "options", [], "any", false, false, true, 21), "attributes", [], "any", false, false, true, 21), "class", [], "any", false, false, true, 21)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                    // line 22
                    yield "        ";
                    $context["nav_link_classes"] = Twig\Extension\CoreExtension::merge(($context["nav_link_classes"] ?? null), [CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["item"], "url", [], "any", false, false, true, 22), "options", [], "any", false, false, true, 22), "attributes", [], "any", false, false, true, 22), "class", [], "any", false, false, true, 22)]);
                    // line 23
                    yield "      ";
                }
                // line 24
                yield "
      <li class=\"dropdown";
                // line 25
                if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["item"], "below", [], "any", false, false, true, 25)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                    yield " ";
                    yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["dropdown_direction"] ?? null), "html", null, true);
                }
                yield "\">
        ";
                // line 26
                if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["item"], "below", [], "any", false, false, true, 26)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                    // line 27
                    yield "          <a href=\"";
                    yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, $context["item"], "url", [], "any", false, false, true, 27), "html", null, true);
                    yield "\" class=\"";
                    yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, Twig\Extension\CoreExtension::join(Twig\Extension\CoreExtension::merge(($context["nav_link_classes"] ?? null), ["dropdown-toggle"]), " "), "html", null, true);
                    yield "\" data-bs-toggle=\"dropdown\" data-bs-auto-close=\"outside\" aria-expanded=\"false\">";
                    yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, $context["item"], "title", [], "any", false, false, true, 27), "html", null, true);
                    yield "</a>
          ";
                    // line 29
                    yield from $this->load("radix:dropdown-menu", 29)->unwrap()->yield(CoreExtension::merge($context, ["items" => CoreExtension::getAttribute($this->env, $this->source,                     // line 30
$context["item"], "below", [], "any", false, false, true, 30), "dropdown_direction" =>                     // line 31
($context["dropdown_direction"] ?? null)]));
                    // line 34
                    yield "        ";
                } else {
                    // line 35
                    yield "          ";
                    $context["route_name"] = (((($tmp = CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["item"], "url", [], "any", false, false, true, 35), "routed", [], "any", false, false, true, 35)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["item"], "url", [], "any", false, false, true, 35), "routeName", [], "any", false, false, true, 35)) : (""));
                    // line 36
                    yield "          ";
                    if (((($context["route_name"] ?? null) == "<nolink>") && (CoreExtension::getAttribute($this->env, $this->source, $context["item"], "title", [], "any", false, false, true, 36) == "-"))) {
                        // line 37
                        yield "            <hr class=\"dropdown-divider\">
          ";
                    } elseif ((                    // line 38
($context["route_name"] ?? null) == "<button>")) {
                        // line 39
                        yield "            ";
                        // line 40
                        yield from $this->load("radix:button", 40)->unwrap()->yield(CoreExtension::merge($context, ["button_html_tag" => "button", "color" => "primary", "content" => CoreExtension::getAttribute($this->env, $this->source,                         // line 43
$context["item"], "title", [], "any", false, false, true, 43), "button_utility_classes" =>                         // line 44
($context["nav_link_classes"] ?? null), "url" => CoreExtension::getAttribute($this->env, $this->source,                         // line 45
$context["item"], "url", [], "any", false, false, true, 45)]));
                        // line 48
                        yield "          ";
                    } else {
                        // line 49
                        yield "            ";
                        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->getLink(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "title", [], "any", false, false, true, 49), CoreExtension::getAttribute($this->env, $this->source, $context["item"], "url", [], "any", false, false, true, 49), ["class" => ($context["nav_link_classes"] ?? null)]), "html", null, true);
                        yield "
          ";
                    }
                    // line 51
                    yield "        ";
                }
                // line 52
                yield "      </li>
    ";
                ++$context['loop']['index0'];
                ++$context['loop']['index'];
                $context['loop']['first'] = false;
                if (isset($context['loop']['revindex0'], $context['loop']['revindex'])) {
                    --$context['loop']['revindex0'];
                    --$context['loop']['revindex'];
                    $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent);
            $context += $_parent;
            // line 54
            yield "  </ul>
";
        }
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["dropdown_menu_utility_classes", "items"]);        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "radix:dropdown-menu";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  174 => 54,  158 => 52,  155 => 51,  149 => 49,  146 => 48,  144 => 45,  143 => 44,  142 => 43,  141 => 40,  139 => 39,  137 => 38,  134 => 37,  131 => 36,  128 => 35,  125 => 34,  123 => 31,  122 => 30,  121 => 29,  112 => 27,  110 => 26,  103 => 25,  100 => 24,  97 => 23,  94 => 22,  91 => 21,  88 => 20,  86 => 19,  83 => 18,  80 => 17,  63 => 16,  58 => 15,  56 => 14,  53 => 13,  51 => 11,  50 => 9,  48 => 7,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "radix:dropdown-menu", "themes/contrib/radix/components/dropdown-menu/dropdown-menu.twig");
    }
    
    public function ensureSecurityChecked(): void
    {
        if ($this->sandbox->isSandboxed($this->source)) {
            $this->checkSecurity();
        }
    }
    
    public function checkSecurity()
    {
        static $tags = ["set" => 7, "if" => 14, "for" => 16, "include" => 29];
        static $filters = ["merge" => 11, "length" => 14, "escape" => 15, "join" => 15];
        static $functions = ["link" => 49];
        static $tests = [];

        try {
            $this->sandbox->checkSecurity(
                [0 => "set", 1 => "if", 2 => "for", 3 => "include"],
                [0 => "merge", 1 => "length", 2 => "escape", 3 => "join"],
                [0 => "link"],
                [],
                $this->source
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->source);

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            } elseif ($e instanceof SecurityNotAllowedTestError && isset($tests[$e->getTestName()])) {
                $e->setTemplateLine($tests[$e->getTestName()]);
            }

            throw $e;
        }

    }
}
