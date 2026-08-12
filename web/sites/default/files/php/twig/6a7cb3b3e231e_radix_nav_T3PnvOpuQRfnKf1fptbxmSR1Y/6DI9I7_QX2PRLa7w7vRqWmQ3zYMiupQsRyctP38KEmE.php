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

/* radix:nav */
class __TwigTemplate_41423e8004546aa56dc8961cbee8744b extends Template
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
            'nav_heading' => [$this, 'block_nav_heading'],
            'nav_items' => [$this, 'block_nav_items'],
        ];
        $this->sandbox = $this->extensions[SandboxExtension::class];
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 1
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\TwigExtension']->attachLibrary("core/components.radix--nav"));
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\ComponentsTwigExtension']->addAdditionalContext($context, "radix:nav"));
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\ComponentsTwigExtension']->validateProps($context, "radix:nav"));
        // line 20
        $macros["menus"] = $this->macros["menus"] = $this;
        // line 22
        $context["alignment_classes"] = ["right" => "justify-content-end", "center" => "justify-content-center", "vertical" => "flex-column", "left" => ""];
        // line 29
        yield "
";
        // line 30
        $context["alignment"] = (((CoreExtension::getAttribute($this->env, $this->source, ($context["alignment_classes"] ?? null), ($context["alignment"] ?? null), [], "array", true, true, true, 30) &&  !(null === (($_v0 = ($context["alignment_classes"] ?? null)) && is_array($_v0) || $_v0 instanceof ArrayAccess && in_array($_v0::class, CoreExtension::ARRAY_LIKE_CLASSES, true) ? ($_v0[(($_v1 = ($context["alignment"] ?? null)) instanceof \Stringable ? (string) $_v1 : $_v1)] ?? null) : CoreExtension::getAttribute($this->env, $this->source, ($context["alignment_classes"] ?? null), ($context["alignment"] ?? null), [], "array", false, false, true, 30))))) ? ((($_v2 = ($context["alignment_classes"] ?? null)) && is_array($_v2) || $_v2 instanceof ArrayAccess && in_array($_v2::class, CoreExtension::ARRAY_LIKE_CLASSES, true) ? ($_v2[(($_v3 = ($context["alignment"] ?? null)) instanceof \Stringable ? (string) $_v3 : $_v3)] ?? null) : CoreExtension::getAttribute($this->env, $this->source, ($context["alignment_classes"] ?? null), ($context["alignment"] ?? null), [], "array", false, false, true, 30))) : (""));
        // line 31
        $context["dropdown_direction"] = (((array_key_exists("dropdown_direction", $context) &&  !(null === $context["dropdown_direction"]))) ? ($context["dropdown_direction"]) : (""));
        // line 32
        $context["style"] = (((($tmp = ($context["style"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? (("nav-" . ($context["style"] ?? null))) : (""));
        // line 33
        $context["fill"] = (((($tmp = ($context["fill"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? (("nav-" . ($context["fill"] ?? null))) : (""));
        // line 34
        $context["is_dropdown"] = ((array_key_exists("is_dropdown", $context)) ? (($context["is_dropdown"] ?? null)) : (true));
        // line 35
        yield "
";
        // line 37
        $context["nav_classes"] = Twig\Extension\CoreExtension::merge(["nav",         // line 39
($context["style"] ?? null),         // line 40
($context["alignment"] ?? null),         // line 41
($context["fill"] ?? null)], (((($tmp =         // line 42
($context["nav_utility_classes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["nav_utility_classes"]) : ([])));
        // line 44
        $context["heading_classes"] = (((($tmp = ($context["heading_utility_classes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["heading_utility_classes"]) : ([]));
        // line 45
        $context["heading_level"] = (((($tmp = ($context["heading_level"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? (($context["heading_level"] ?? null)) : ((((CoreExtension::getAttribute($this->env, $this->source, ($context["heading"] ?? null), "level", [], "any", true, true, true, 45) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["heading"] ?? null), "level", [], "any", false, false, true, 45)))) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["heading"] ?? null), "level", [], "any", false, false, true, 45)) : ("h2"))));
        // line 46
        yield "
";
        // line 47
        if ((($tmp = ($context["items"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 48
            if ((($tmp = ($context["heading"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 49
                yield "<";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["heading_level"] ?? null), "html", null, true);
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["heading_attributes"] ?? null), "addClass", [($context["heading_classes"] ?? null)], "method", false, false, true, 49), "html", null, true);
                yield ">
      ";
                // line 50
                yield from $this->unwrap()->yieldBlock('nav_heading', $context, $blocks);
                // line 53
                yield "      </";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["heading_level"] ?? null), "html", null, true);
                yield ">";
            }
            // line 55
            yield "<ul ";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [($context["nav_classes"] ?? null)], "method", false, false, true, 55), "html", null, true);
            yield ">
    ";
            // line 56
            yield from $this->unwrap()->yieldBlock('nav_items', $context, $blocks);
            // line 59
            yield "  </ul>
";
        }
        // line 61
        yield "
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["_self", "nav_utility_classes", "heading_utility_classes", "heading", "items", "heading_attributes", "attributes", "menus", "nav_item_utility_classes", "nav_link_utility_classes"]);        yield from [];
    }

    // line 50
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_nav_heading(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 51
        yield "        ";
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["heading"] ?? null), "text", [], "any", false, false, true, 51), "html", null, true);
        yield "
      ";
        yield from [];
    }

    // line 56
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_nav_items(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 57
        yield "      ";
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($macros["menus"]->getTemplateForMacro("macro_nav_items", $context, 57, $this->getSourceContext())->macro_nav_items(...[($context["menus"] ?? null), ($context["items"] ?? null), ($context["is_dropdown"] ?? null), ($context["dropdown_direction"] ?? null), ($context["nav_item_utility_classes"] ?? null), ($context["nav_link_utility_classes"] ?? null)]));
        yield "
    ";
        yield from [];
    }

    // line 85
    public function macro_nav_items($menus = null, $items = null, $is_dropdown = null, $dropdown_direction = null, $nav_item_utility_classes = null, $nav_link_utility_classes = null, ...$varargs): string|Markup
    {
        $macros = $this->macros;
        $context = [
            "menus" => $menus,
            "items" => $items,
            "is_dropdown" => $is_dropdown,
            "dropdown_direction" => $dropdown_direction,
            "nav_item_utility_classes" => $nav_item_utility_classes,
            "nav_link_utility_classes" => $nav_link_utility_classes,
            "varargs" => $varargs,
        ] + $this->env->getGlobals();

        $blocks = [];

        return ('' === $tmp = implode('', iterator_to_array((function () use (&$context, $macros, $blocks) {
            // line 86
            yield "  ";
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
                // line 87
                yield "    ";
                // line 88
                $context["nav_item_classes"] = Twig\Extension\CoreExtension::merge(["nav-item", (((($tmp = CoreExtension::getAttribute($this->env, $this->source,                 // line 90
$context["item"], "in_active_trail", [], "any", false, false, true, 90)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("active") : ("")), (((((($tmp =                 // line 91
($context["is_dropdown"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp) && (($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["item"], "is_expanded", [], "any", false, false, true, 91)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) && (($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["item"], "below", [], "any", false, false, true, 91)) && $tmp instanceof Markup ? (string) $tmp : $tmp))) ? ("dropdown") : (""))], (((($tmp =                 // line 92
($context["nav_item_utility_classes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["nav_item_utility_classes"]) : ([])));
                // line 94
                yield "    ";
                // line 95
                $context["nav_link_classes"] = Twig\Extension\CoreExtension::merge(["nav-link", (((($tmp = CoreExtension::getAttribute($this->env, $this->source,                 // line 97
$context["item"], "in_active_trail", [], "any", false, false, true, 97)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("active") : (""))], (((($tmp =                 // line 98
($context["nav_link_utility_classes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["nav_link_utility_classes"]) : ([])));
                // line 100
                yield "    ";
                if (is_iterable(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["item"], "url", [], "any", false, false, true, 100), "options", [], "any", false, false, true, 100), "attributes", [], "any", false, false, true, 100), "class", [], "any", false, false, true, 100))) {
                    // line 101
                    yield "      ";
                    $context["nav_link_classes"] = Twig\Extension\CoreExtension::merge(($context["nav_link_classes"] ?? null), CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["item"], "url", [], "any", false, false, true, 101), "options", [], "any", false, false, true, 101), "attributes", [], "any", false, false, true, 101), "class", [], "any", false, false, true, 101));
                    // line 102
                    yield "    ";
                } elseif ((($tmp = CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["item"], "url", [], "any", false, false, true, 102), "options", [], "any", false, false, true, 102), "attributes", [], "any", false, false, true, 102), "class", [], "any", false, false, true, 102)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                    // line 103
                    yield "      ";
                    $context["nav_link_classes"] = Twig\Extension\CoreExtension::merge(($context["nav_link_classes"] ?? null), [CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["item"], "url", [], "any", false, false, true, 103), "options", [], "any", false, false, true, 103), "attributes", [], "any", false, false, true, 103), "class", [], "any", false, false, true, 103)]);
                    // line 104
                    yield "    ";
                }
                // line 105
                yield "    <li";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["item"], "attributes", [], "any", false, false, true, 105), "addClass", [($context["nav_item_classes"] ?? null)], "method", false, false, true, 105), "html", null, true);
                yield ">
      ";
                // line 106
                if ((((($tmp = ($context["is_dropdown"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp) && (($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["item"], "is_expanded", [], "any", false, false, true, 106)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) && (($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["item"], "below", [], "any", false, false, true, 106)) && $tmp instanceof Markup ? (string) $tmp : $tmp))) {
                    // line 107
                    yield "        ";
                    yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->getLink(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "title", [], "any", false, false, true, 107), CoreExtension::getAttribute($this->env, $this->source, $context["item"], "url", [], "any", false, false, true, 107), ["class" => Twig\Extension\CoreExtension::merge(($context["nav_link_classes"] ?? null), ["dropdown-toggle"]), "data-bs-toggle" => "dropdown", "data-bs-auto-close" => "outside", "aria-expanded" => "false"]), "html", null, true);
                    yield "
        ";
                    // line 109
                    yield from $this->load("radix:dropdown-menu", 109)->unwrap()->yield(CoreExtension::merge($context, ["items" => CoreExtension::getAttribute($this->env, $this->source,                     // line 110
$context["item"], "below", [], "any", false, false, true, 110), "dropdown_direction" =>                     // line 111
($context["dropdown_direction"] ?? null)]));
                    // line 114
                    yield "      ";
                } else {
                    // line 115
                    yield "        ";
                    if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["item"], "url", [], "any", false, false, true, 115)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                        // line 116
                        yield "          ";
                        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->getLink(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "title", [], "any", false, false, true, 116), CoreExtension::getAttribute($this->env, $this->source, $context["item"], "url", [], "any", false, false, true, 116), ["class" => ($context["nav_link_classes"] ?? null)]), "html", null, true);
                        yield "
        ";
                    } elseif ((($tmp = CoreExtension::getAttribute($this->env, $this->source,                     // line 117
$context["item"], "link", [], "any", false, false, true, 117)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                        // line 118
                        yield "          ";
                        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, $context["item"], "link", [], "any", false, false, true, 118), "html", null, true);
                        yield "
        ";
                    }
                    // line 120
                    yield "
        ";
                    // line 121
                    if ((((!(($tmp = ($context["is_dropdown"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) && (($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["item"], "is_expanded", [], "any", false, false, true, 121)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) && (($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["item"], "below", [], "any", false, false, true, 121)) && $tmp instanceof Markup ? (string) $tmp : $tmp))) {
                        // line 122
                        yield "          <ul class=\"nav flex-column ms-3\">
            ";
                        // line 123
                        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($macros["menus"]->getTemplateForMacro("macro_nav_items", $context, 123, $this->getSourceContext())->macro_nav_items(...[($context["menus"] ?? null), CoreExtension::getAttribute($this->env, $this->source, $context["item"], "below", [], "any", false, false, true, 123), ($context["is_dropdown"] ?? null), ($context["dropdown_direction"] ?? null), ($context["nav_item_utility_classes"] ?? null), ($context["nav_link_utility_classes"] ?? null)]));
                        yield "
          </ul>
        ";
                    }
                    // line 126
                    yield "      ";
                }
                // line 127
                yield "    </li>
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
            yield from [];
        })(), false))) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "radix:nav";
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
        return array (  263 => 127,  260 => 126,  254 => 123,  251 => 122,  249 => 121,  246 => 120,  240 => 118,  238 => 117,  233 => 116,  230 => 115,  227 => 114,  225 => 111,  224 => 110,  223 => 109,  218 => 107,  216 => 106,  211 => 105,  208 => 104,  205 => 103,  202 => 102,  199 => 101,  196 => 100,  194 => 98,  193 => 97,  192 => 95,  190 => 94,  188 => 92,  187 => 91,  186 => 90,  185 => 88,  183 => 87,  165 => 86,  148 => 85,  140 => 57,  133 => 56,  125 => 51,  118 => 50,  111 => 61,  107 => 59,  105 => 56,  100 => 55,  95 => 53,  93 => 50,  87 => 49,  85 => 48,  83 => 47,  80 => 46,  78 => 45,  76 => 44,  74 => 42,  73 => 41,  72 => 40,  71 => 39,  70 => 37,  67 => 35,  65 => 34,  63 => 33,  61 => 32,  59 => 31,  57 => 30,  54 => 29,  52 => 22,  50 => 20,  46 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "radix:nav", "themes/contrib/radix/components/nav/nav.twig");
    }
    
    public function ensureSecurityChecked(): void
    {
        if ($this->sandbox->isSandboxed($this->source)) {
            $this->checkSecurity();
        }
    }
    
    public function checkSecurity()
    {
        static $tags = ["import" => 20, "set" => 22, "if" => 47, "block" => 50, "macro" => 85, "for" => 86, "include" => 109];
        static $filters = ["merge" => 42, "escape" => 49];
        static $functions = ["link" => 107];
        static $tests = [];

        try {
            $this->sandbox->checkSecurity(
                [0 => "import", 1 => "set", 2 => "if", 3 => "block", 4 => "macro", 5 => "for", 6 => "include"],
                [0 => "merge", 1 => "escape"],
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
