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

/* radix:fieldset */
class __TwigTemplate_a810f414d283824565905ca5dc5430cb extends Template
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
            'fieldset_prefix' => [$this, 'block_fieldset_prefix'],
            'children' => [$this, 'block_children'],
            'fieldset_suffix' => [$this, 'block_fieldset_suffix'],
        ];
        $this->sandbox = $this->extensions[SandboxExtension::class];
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 1
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\TwigExtension']->attachLibrary("core/components.radix--fieldset"));
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\ComponentsTwigExtension']->addAdditionalContext($context, "radix:fieldset"));
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\ComponentsTwigExtension']->validateProps($context, "radix:fieldset"));
        // line 34
        $_v0 = ('' === $tmp = implode('', iterator_to_array((function () use (&$context, $macros, $blocks) {
            // line 35
            yield "
";
            // line 37
            $context["fieldset_classes"] = Twig\Extension\CoreExtension::merge(["js-form-item", "form-item", "form-wrapper", "js-form-wrapper"], (((($tmp =             // line 42
($context["fieldset_utility_classes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["fieldset_utility_classes"]) : ([])));
            // line 44
            yield "
";
            // line 46
            $context["legend_classes"] = Twig\Extension\CoreExtension::merge(["fieldset-legend"], (((($tmp =             // line 48
($context["legend_utility_classes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["legend_utility_classes"]) : ([])));
            // line 50
            yield "
";
            // line 52
            $context["legend_title_classes"] = Twig\Extension\CoreExtension::merge([(((($tmp =             // line 53
($context["required"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("js-form-required form-required") : (""))], (((($tmp =             // line 54
($context["legend_title_utility_classes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["legend_title_utility_classes"]) : ([])));
            // line 56
            yield "
";
            // line 58
            $context["fieldset_description_classes"] = Twig\Extension\CoreExtension::merge(["js-form-item", "form-item", "form-wrapper", "js-form-wrapper"], (((($tmp =             // line 63
($context["fieldset_description_utility_classes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["fieldset_description_utility_classes"]) : ([])));
            // line 65
            yield "
";
            // line 67
            $context["prefix_classes"] = Twig\Extension\CoreExtension::merge(["fieldset-prefix"], (((($tmp =             // line 69
($context["prefix_utility_classes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["prefix_utility_classes"]) : ([])));
            // line 71
            yield "
";
            // line 73
            $context["suffix_classes"] = Twig\Extension\CoreExtension::merge(["fieldset-prefix"], (((($tmp =             // line 75
($context["suffix_utility_classes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["suffix_utility_classes"]) : ([])));
            // line 77
            yield "
";
            // line 78
            $context["fieldset_content_classes"] = (((array_key_exists("fieldset_content_utility_classes", $context) &&  !(null === $context["fieldset_content_utility_classes"]))) ? ($context["fieldset_content_utility_classes"]) : (["mb-3"]));
            // line 79
            $context["disabled_attr"] = (((array_key_exists("disabled", $context) && (($tmp = ($context["disabled"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp))) ? ("disabled") : (null));
            // line 80
            yield "
";
            // line 81
            $context["fieldset_content_attributes"] = (((($tmp = ($context["fieldset_content_attributes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["fieldset_content_attributes"]) : ($this->extensions['Drupal\Core\Template\TwigExtension']->createAttribute()));
            // line 82
            $context["fieldset_legend_title_attributes"] = (((($tmp = ($context["fieldset_legend_title_attributes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["fieldset_legend_title_attributes"]) : ($this->extensions['Drupal\Core\Template\TwigExtension']->createAttribute()));
            // line 83
            $context["fieldset_prefix_attributes"] = (((($tmp = ($context["fieldset_prefix_attributes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["fieldset_prefix_attributes"]) : ($this->extensions['Drupal\Core\Template\TwigExtension']->createAttribute()));
            // line 84
            $context["fieldset_suffix_attributes"] = (((($tmp = ($context["fieldset_suffix_attributes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["fieldset_suffix_attributes"]) : ($this->extensions['Drupal\Core\Template\TwigExtension']->createAttribute()));
            // line 85
            yield "

<fieldset ";
            // line 87
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [($context["fieldset_classes"] ?? null)], "method", false, false, true, 87), "setAttribute", [($context["disabled_attr"] ?? null), ($context["disabled_attr"] ?? null)], "method", false, false, true, 87), "html", null, true);
            yield ">
  ";
            // line 88
            if ((($context["title_display"] ?? null) != "none")) {
                // line 89
                yield "    <legend ";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["legend"] ?? null), "attributes", [], "any", false, false, true, 89), "addClass", [($context["legend_classes"] ?? null)], "method", false, false, true, 89), "html", null, true);
                yield ">
      <label ";
                // line 90
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["fieldset_legend_title_attributes"] ?? null), "addClass", [($context["legend_title_classes"] ?? null)], "method", false, false, true, 90), "html", null, true);
                yield ">";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["legend"] ?? null), "title", [], "any", false, false, true, 90), "html", null, true);
                yield "</label>
    </legend>
  ";
            }
            // line 93
            yield "
  <div ";
            // line 94
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["fieldset_content_attributes"] ?? null), "addClass", [($context["fieldset_content_classes"] ?? null)], "method", false, false, true, 94), "html", null, true);
            yield ">
    ";
            // line 95
            if (((($context["description_display"] ?? null) == "before") && (($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["description"] ?? null), "content", [], "any", false, false, true, 95)) && $tmp instanceof Markup ? (string) $tmp : $tmp))) {
                // line 96
                yield "      <small ";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["description"] ?? null), "attributes", [], "any", false, false, true, 96), "addClass", ["description", "form-text", "text-muted"], "method", false, false, true, 96), "html", null, true);
                yield ">";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["description"] ?? null), "content", [], "any", false, false, true, 96), "html", null, true);
                yield "</small>
    ";
            }
            // line 98
            yield "
    ";
            // line 99
            if ((($tmp = ($context["errors"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 100
                yield "      <div class=\"invalid-feedback\">
        ";
                // line 101
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["errors"] ?? null), "html", null, true);
                yield "
      </div>
    ";
            }
            // line 104
            yield "
    ";
            // line 105
            yield from $this->unwrap()->yieldBlock('fieldset_prefix', $context, $blocks);
            // line 110
            yield "
    ";
            // line 111
            yield from $this->unwrap()->yieldBlock('children', $context, $blocks);
            // line 114
            yield "
    ";
            // line 115
            yield from $this->unwrap()->yieldBlock('fieldset_suffix', $context, $blocks);
            // line 120
            yield "
    ";
            // line 121
            if ((CoreExtension::inFilter(($context["description_display"] ?? null), ["after", "invisible"]) && (($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["description"] ?? null), "content", [], "any", false, false, true, 121)) && $tmp instanceof Markup ? (string) $tmp : $tmp))) {
                // line 122
                yield "      <small ";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["description"] ?? null), "attributes", [], "any", false, false, true, 122), "addClass", [($context["fieldset_description_classes"] ?? null)], "method", false, false, true, 122), "html", null, true);
                yield ">";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["description"] ?? null), "content", [], "any", false, false, true, 122), "html", null, true);
                yield "</small>
    ";
            }
            // line 124
            yield "  </div>
</fieldset>

";
            yield from [];
        })(), false))) ? '' : new Markup($tmp, $this->env->getCharset());
        // line 34
        yield (string) Twig\Extension\CoreExtension::spaceless($this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $_v0, "html", null, true));
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["fieldset_utility_classes", "legend_utility_classes", "required", "legend_title_utility_classes", "fieldset_description_utility_classes", "prefix_utility_classes", "suffix_utility_classes", "fieldset_content_utility_classes", "disabled", "attributes", "title_display", "legend", "description_display", "description", "errors", "prefix", "children", "suffix"]);        yield from [];
    }

    // line 105
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_fieldset_prefix(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 106
        yield "      ";
        if ((($tmp = ($context["prefix"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 107
            yield "        <span ";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["fieldset_prefix_attributes"] ?? null), "addClass", [($context["prefix_classes"] ?? null)], "method", false, false, true, 107), "html", null, true);
            yield ">";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["prefix"] ?? null), "html", null, true);
            yield "</span>
      ";
        }
        // line 109
        yield "    ";
        yield from [];
    }

    // line 111
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_children(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 112
        yield "      ";
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["children"] ?? null), "html", null, true);
        yield "
    ";
        yield from [];
    }

    // line 115
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_fieldset_suffix(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 116
        yield "      ";
        if ((($tmp = ($context["suffix"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 117
            yield "        <span ";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["fieldset_suffix_attributes"] ?? null), "addClass", [($context["suffix_classes"] ?? null)], "method", false, false, true, 117), "html", null, true);
            yield ">";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["suffix"] ?? null), "html", null, true);
            yield "</span>
      ";
        }
        // line 119
        yield "    ";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "radix:fieldset";
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
        return array (  259 => 119,  251 => 117,  248 => 116,  241 => 115,  233 => 112,  226 => 111,  221 => 109,  213 => 107,  210 => 106,  203 => 105,  197 => 34,  190 => 124,  182 => 122,  180 => 121,  177 => 120,  175 => 115,  172 => 114,  170 => 111,  167 => 110,  165 => 105,  162 => 104,  156 => 101,  153 => 100,  151 => 99,  148 => 98,  140 => 96,  138 => 95,  134 => 94,  131 => 93,  123 => 90,  118 => 89,  116 => 88,  112 => 87,  108 => 85,  106 => 84,  104 => 83,  102 => 82,  100 => 81,  97 => 80,  95 => 79,  93 => 78,  90 => 77,  88 => 75,  87 => 73,  84 => 71,  82 => 69,  81 => 67,  78 => 65,  76 => 63,  75 => 58,  72 => 56,  70 => 54,  69 => 53,  68 => 52,  65 => 50,  63 => 48,  62 => 46,  59 => 44,  57 => 42,  56 => 37,  53 => 35,  51 => 34,  47 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "radix:fieldset", "themes/contrib/radix/components/fieldset/fieldset.twig");
    }
    
    public function ensureSecurityChecked(): void
    {
        if ($this->sandbox->isSandboxed($this->source)) {
            $this->checkSecurity();
        }
    }
    
    public function checkSecurity()
    {
        static $tags = ["apply" => 34, "set" => 37, "if" => 88, "block" => 105];
        static $filters = ["merge" => 42, "escape" => 87, "spaceless" => 34];
        static $functions = ["create_attribute" => 81];
        static $tests = [];

        try {
            $this->sandbox->checkSecurity(
                [0 => "apply", 1 => "set", 2 => "if", 3 => "block"],
                [0 => "merge", 1 => "escape", 2 => "spaceless"],
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
