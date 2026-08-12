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

/* radix:pagination */
class __TwigTemplate_d632722d29d2fc625b378a1959d751a4 extends Template
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
            'pagination_previous' => [$this, 'block_pagination_previous'],
            'pagination_next' => [$this, 'block_pagination_next'],
        ];
        $this->sandbox = $this->extensions[SandboxExtension::class];
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 1
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\TwigExtension']->attachLibrary("core/components.radix--pagination"));
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\ComponentsTwigExtension']->addAdditionalContext($context, "radix:pagination"));
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\ComponentsTwigExtension']->validateProps($context, "radix:pagination"));
        // line 14
        $context["pagination_classes"] = Twig\Extension\CoreExtension::merge(["pagination-wrapper"], (((($tmp =         // line 16
($context["pagination_utility_classes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["pagination_utility_classes"]) : ([])));
        // line 18
        yield "
";
        // line 19
        $context["pagination_attributes"] = (((($tmp = ($context["attributes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["attributes"]) : ($this->extensions['Drupal\Core\Template\TwigExtension']->createAttribute()));
        // line 20
        yield "
";
        // line 22
        $context["alignment_classes"] = ["end" => "justify-content-end", "center" => "justify-content-center", "vertical" => "flex-column", "start" => ""];
        // line 29
        yield "
";
        // line 30
        $context["alignment"] = (((CoreExtension::getAttribute($this->env, $this->source, ($context["alignment_classes"] ?? null), ($context["alignment"] ?? null), [], "array", true, true, true, 30) &&  !(null === (($_v0 = ($context["alignment_classes"] ?? null)) && is_array($_v0) || $_v0 instanceof ArrayAccess && in_array($_v0::class, CoreExtension::ARRAY_LIKE_CLASSES, true) ? ($_v0[(($_v1 = ($context["alignment"] ?? null)) instanceof \Stringable ? (string) $_v1 : $_v1)] ?? null) : CoreExtension::getAttribute($this->env, $this->source, ($context["alignment_classes"] ?? null), ($context["alignment"] ?? null), [], "array", false, false, true, 30))))) ? ((($_v2 = ($context["alignment_classes"] ?? null)) && is_array($_v2) || $_v2 instanceof ArrayAccess && in_array($_v2::class, CoreExtension::ARRAY_LIKE_CLASSES, true) ? ($_v2[(($_v3 = ($context["alignment"] ?? null)) instanceof \Stringable ? (string) $_v3 : $_v3)] ?? null) : CoreExtension::getAttribute($this->env, $this->source, ($context["alignment_classes"] ?? null), ($context["alignment"] ?? null), [], "array", false, false, true, 30))) : (""));
        // line 31
        $context["show_last"] = (((array_key_exists("show_last", $context) &&  !(null === $context["show_last"]))) ? ($context["show_last"]) : (true));
        // line 32
        $context["show_first"] = (((array_key_exists("show_first", $context) &&  !(null === $context["show_first"]))) ? ($context["show_first"]) : (true));
        // line 33
        $context["show_ellipsis"] = (((array_key_exists("show_ellipsis", $context) &&  !(null === $context["show_ellipsis"]))) ? ($context["show_ellipsis"]) : (true));
        // line 34
        yield "
";
        // line 35
        if ((($tmp = ($context["items"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 36
            yield "  <nav ";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["pagination_attributes"] ?? null), "addClass", [($context["pagination_classes"] ?? null)], "method", false, false, true, 36), "html", null, true);
            yield " role=\"navigation\" aria-label=\"";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Pagination"));
            yield "\">
    <ul
      class=\"pagination pager__items js-pager__items d-flex flex-wrap ";
            // line 38
            yield (string) (((($tmp = ($context["size"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ("pagination-" . ($context["size"] ?? null)), "html", null, true)) : (""));
            yield " ";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["alignment"] ?? null), "html", null, true);
            yield "\">
      ";
            // line 40
            yield "      ";
            if (((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "first", [], "any", false, false, true, 40)) && $tmp instanceof Markup ? (string) $tmp : $tmp) && (($tmp = ($context["show_first"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp))) {
                // line 41
                yield "        <li class=\"page-item pager__item pager__item--first\">
          <a href=\"";
                // line 42
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "first", [], "any", false, false, true, 42), "href", [], "any", false, false, true, 42), "html", null, true);
                yield "\" title=\"";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Go to first page"));
                yield "\" ";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->withoutFilter(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "first", [], "any", false, false, true, 42), "attributes", [], "any", false, false, true, 42), "href", "title"), "html", null, true);
                yield " class=\"page-link\">
            <span class=\"visually-hidden\">";
                // line 43
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("First page"));
                yield "</span>
            <span aria-hidden=\"true\">";
                // line 44
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "first", [], "any", false, true, true, 44), "text", [], "any", true, true, true, 44)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "first", [], "any", false, false, true, 44), "text", [], "any", false, false, true, 44), t("« First"))) : (t("« First"))), "html", null, true);
                yield "</span>
          </a>
        </li>
      ";
            }
            // line 48
            yield "
      ";
            // line 50
            yield "      ";
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "previous", [], "any", false, false, true, 50)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 51
                yield "        <li class=\"page-item pager__item pager__item--previous\">
          ";
                // line 52
                yield from $this->unwrap()->yieldBlock('pagination_previous', $context, $blocks);
                // line 58
                yield "        </li>
      ";
            }
            // line 60
            yield "
      ";
            // line 62
            yield "      ";
            if (((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["ellipses"] ?? null), "previous", [], "any", false, false, true, 62)) && $tmp instanceof Markup ? (string) $tmp : $tmp) && (($tmp = ($context["show_ellipsis"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp))) {
                // line 63
                yield "        <li class=\"page-item pager__item pager__item--ellipsis disabled\">
          <span class=\"page-link\" aria-hidden=\"true\">&hellip;</span>
          <span class=\"visually-hidden\">";
                // line 65
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("More pages"));
                yield "</span>
        </li>
      ";
            }
            // line 68
            yield "
      ";
            // line 70
            yield "      ";
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "pages", [], "any", false, false, true, 70));
            foreach ($context['_seq'] as $context["key"] => $context["item"]) {
                // line 71
                yield "        <li class=\"page-item pager__item";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar((((($context["current"] ?? null) == $context["key"])) ? (" is-active active") : ("")));
                yield "\">
          ";
                // line 72
                if ((($context["current"] ?? null) == $context["key"])) {
                    // line 73
                    yield "            ";
                    $context["title"] = t("Current page");
                    // line 74
                    yield "          ";
                } else {
                    // line 75
                    yield "            ";
                    $context["title"] = t("Go to page @key", ["@key" => $context["key"]]);
                    // line 76
                    yield "          ";
                }
                // line 77
                yield "          <a href=\"";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, $context["item"], "href", [], "any", false, false, true, 77), "html", null, true);
                yield "\" title=\"";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["title"] ?? null), "html", null, true);
                yield "\" ";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->withoutFilter(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "attributes", [], "any", false, false, true, 77), "href", "title"), "html", null, true);
                yield " class=\"page-link\">
            <span class=\"visually-hidden\">
              ";
                // line 79
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar((((($context["current"] ?? null) == $context["key"])) ? (t("Current page")) : (t("Page"))));
                yield "
            </span>";
                // line 81
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $context["key"], "html", null, true);
                // line 82
                yield "</a>
        </li>
      ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['key'], $context['item'], $context['_parent']);
            $context = array_intersect_key($context, $_parent);
            $context += $_parent;
            // line 85
            yield "
      ";
            // line 86
            if (((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "current", [], "any", false, false, true, 86)) && $tmp instanceof Markup ? (string) $tmp : $tmp) && ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "previous", [], "any", false, false, true, 86)) && $tmp instanceof Markup ? (string) $tmp : $tmp) || (($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "next", [], "any", false, false, true, 86)) && $tmp instanceof Markup ? (string) $tmp : $tmp)))) {
                // line 87
                yield "        <li class=\"page-item disabled\">
          <span class=\"page-link\">
            ";
                // line 89
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Page"));
                yield " ";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "current", [], "any", false, false, true, 89), "html", null, true);
                yield "
          </span>
        </li>
      ";
            }
            // line 93
            yield "
      ";
            // line 95
            yield "      ";
            if (((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["ellipses"] ?? null), "next", [], "any", false, false, true, 95)) && $tmp instanceof Markup ? (string) $tmp : $tmp) && (($tmp = ($context["show_ellipsis"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp))) {
                // line 96
                yield "        <li class=\"page-item pager__item pager__item--ellipsis disabled\">
          <span class=\"page-link\" aria-hidden=\"true\">&hellip;</span>
          <span class=\"visually-hidden\">";
                // line 98
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("More pages"));
                yield "</span>
        </li>
      ";
            }
            // line 101
            yield "
      ";
            // line 103
            yield "      ";
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "next", [], "any", false, false, true, 103)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 104
                yield "        <li class=\"page-item pager__item pager__item--next\">
          ";
                // line 105
                yield from $this->unwrap()->yieldBlock('pagination_next', $context, $blocks);
                // line 111
                yield "        </li>
      ";
            }
            // line 113
            yield "
      ";
            // line 115
            yield "      ";
            if (((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "last", [], "any", false, false, true, 115)) && $tmp instanceof Markup ? (string) $tmp : $tmp) && (($tmp = ($context["show_last"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp))) {
                // line 116
                yield "        <li class=\"page-item pager__item pager__item--last\">
          <a href=\"";
                // line 117
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "last", [], "any", false, false, true, 117), "href", [], "any", false, false, true, 117), "html", null, true);
                yield "\" title=\"";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Go to last page"));
                yield "\" ";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->withoutFilter(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "last", [], "any", false, false, true, 117), "attributes", [], "any", false, false, true, 117), "href", "title"), "html", null, true);
                yield " class=\"page-link\">
            <span class=\"visually-hidden\">";
                // line 118
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Last page"));
                yield "</span>
            <span aria-hidden=\"true\">";
                // line 119
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "last", [], "any", false, true, true, 119), "text", [], "any", true, true, true, 119)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "last", [], "any", false, false, true, 119), "text", [], "any", false, false, true, 119), t("Last »"))) : (t("Last »"))), "html", null, true);
                yield "</span>
          </a>
        </li>
      ";
            }
            // line 123
            yield "    </ul>
  </nav>
";
        }
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["pagination_utility_classes", "attributes", "items", "size", "ellipses", "current"]);        yield from [];
    }

    // line 52
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_pagination_previous(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 53
        yield "            <a href=\"";
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "previous", [], "any", false, false, true, 53), "href", [], "any", false, false, true, 53), "html", null, true);
        yield "\" title=\"";
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Go to previous page"));
        yield "\" rel=\"prev\" ";
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->withoutFilter(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "previous", [], "any", false, false, true, 53), "attributes", [], "any", false, false, true, 53), "href", "title", "rel"), "html", null, true);
        yield " class=\"page-link\">
              <span class=\"visually-hidden\">";
        // line 54
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Previous page"));
        yield "</span>
              <span aria-hidden=\"true\">";
        // line 55
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "previous", [], "any", false, true, true, 55), "text", [], "any", true, true, true, 55)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "previous", [], "any", false, false, true, 55), "text", [], "any", false, false, true, 55), t("‹ Previous"))) : (t("‹ Previous"))), "html", null, true);
        yield "</span>
            </a>
          ";
        yield from [];
    }

    // line 105
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_pagination_next(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 106
        yield "            <a href=\"";
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "next", [], "any", false, false, true, 106), "href", [], "any", false, false, true, 106), "html", null, true);
        yield "\" title=\"";
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Go to next page"));
        yield "\" rel=\"next\" ";
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->withoutFilter(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "next", [], "any", false, false, true, 106), "attributes", [], "any", false, false, true, 106), "href", "title", "rel"), "html", null, true);
        yield " class=\"page-link\">
              <span class=\"visually-hidden\">";
        // line 107
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Next page"));
        yield "</span>
              <span aria-hidden=\"true\">";
        // line 108
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "next", [], "any", false, true, true, 108), "text", [], "any", true, true, true, 108)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "next", [], "any", false, false, true, 108), "text", [], "any", false, false, true, 108), t("Next ›"))) : (t("Next ›"))), "html", null, true);
        yield "</span>
            </a>
          ";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "radix:pagination";
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
        return array (  335 => 108,  331 => 107,  322 => 106,  315 => 105,  307 => 55,  303 => 54,  294 => 53,  287 => 52,  278 => 123,  271 => 119,  267 => 118,  259 => 117,  256 => 116,  253 => 115,  250 => 113,  246 => 111,  244 => 105,  241 => 104,  238 => 103,  235 => 101,  229 => 98,  225 => 96,  222 => 95,  219 => 93,  210 => 89,  206 => 87,  204 => 86,  201 => 85,  192 => 82,  190 => 81,  186 => 79,  176 => 77,  173 => 76,  170 => 75,  167 => 74,  164 => 73,  162 => 72,  157 => 71,  152 => 70,  149 => 68,  143 => 65,  139 => 63,  136 => 62,  133 => 60,  129 => 58,  127 => 52,  124 => 51,  121 => 50,  118 => 48,  111 => 44,  107 => 43,  99 => 42,  96 => 41,  93 => 40,  87 => 38,  79 => 36,  77 => 35,  74 => 34,  72 => 33,  70 => 32,  68 => 31,  66 => 30,  63 => 29,  61 => 22,  58 => 20,  56 => 19,  53 => 18,  51 => 16,  50 => 14,  46 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "radix:pagination", "themes/contrib/radix/components/pagination/pagination.twig");
    }
    
    public function ensureSecurityChecked(): void
    {
        if ($this->sandbox->isSandboxed($this->source)) {
            $this->checkSecurity();
        }
    }
    
    public function checkSecurity()
    {
        static $tags = ["set" => 14, "if" => 35, "block" => 52, "for" => 70];
        static $filters = ["merge" => 16, "escape" => 36, "t" => 36, "without" => 42, "default" => 44];
        static $functions = ["create_attribute" => 19];
        static $tests = [];

        try {
            $this->sandbox->checkSecurity(
                [0 => "set", 1 => "if", 2 => "block", 3 => "for"],
                [0 => "merge", 1 => "escape", 2 => "t", 3 => "without", 4 => "default"],
                [0 => "create_attribute"],
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
