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

/* radix:field */
class __TwigTemplate_c6b5c8a5d9c81c4a28af2ebaf857c397 extends Template
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
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\TwigExtension']->attachLibrary("core/components.radix--field"));
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\ComponentsTwigExtension']->addAdditionalContext($context, "radix:field"));
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\ComponentsTwigExtension']->validateProps($context, "radix:field"));
        // line 43
        $context["field_classes"] = Twig\Extension\CoreExtension::merge(["field", ("field--name-" . \Drupal\Component\Utility\Html::getClass(        // line 45
($context["field_name"] ?? null))), ("field--type-" . \Drupal\Component\Utility\Html::getClass(        // line 46
($context["field_type"] ?? null))), ("field--label-" .         // line 47
($context["label_display"] ?? null)), (((        // line 48
($context["label_display"] ?? null) == "inline")) ? ("d-flex") : ("")), (((($tmp =         // line 49
($context["multiple"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("field--items") : ("field--item"))], (((($tmp =         // line 50
($context["field_utility_classes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["field_utility_classes"]) : ([])));
        // line 52
        yield "
";
        // line 54
        $context["field_item_classes"] = Twig\Extension\CoreExtension::merge(["field__item"], (((($tmp =         // line 56
($context["field_item_utility_classes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["field_item_utility_classes"]) : ([])));
        // line 58
        yield "
";
        // line 60
        $context["field_title_classes"] = Twig\Extension\CoreExtension::merge(["field__label", (((        // line 62
($context["label_display"] ?? null) == "visually_hidden")) ? ("visually-hidden") : (""))], (((($tmp =         // line 63
($context["field_title_utility_classes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["field_title_utility_classes"]) : ([])));
        // line 65
        yield "
";
        // line 66
        if ((($tmp = ($context["label_hidden"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 67
            yield "  ";
            if ((($tmp = ($context["multiple"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 68
                yield "    <div ";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [($context["field_classes"] ?? null)], "method", false, false, true, 68), "html", null, true);
                yield ">
      ";
                // line 69
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable(($context["items"] ?? null));
                foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                    // line 70
                    yield "        <div ";
                    yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["item"], "attributes", [], "any", false, false, true, 70), "addClass", [($context["field_item_classes"] ?? null)], "method", false, false, true, 70), "html", null, true);
                    yield ">";
                    yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, $context["item"], "content", [], "any", false, false, true, 70), "html", null, true);
                    yield "</div>
      ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_key'], $context['item'], $context['_parent']);
                $context = array_intersect_key($context, $_parent);
                $context += $_parent;
                // line 72
                yield "    </div>
  ";
            } else {
                // line 74
                yield "    ";
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable(($context["items"] ?? null));
                foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                    // line 75
                    yield "      <div ";
                    yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [($context["field_classes"] ?? null)], "method", false, false, true, 75), "html", null, true);
                    yield ">";
                    yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, $context["item"], "content", [], "any", false, false, true, 75), "html", null, true);
                    yield "</div>
    ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_key'], $context['item'], $context['_parent']);
                $context = array_intersect_key($context, $_parent);
                $context += $_parent;
                // line 77
                yield "  ";
            }
        } else {
            // line 79
            yield "  <div ";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [($context["field_classes"] ?? null)], "method", false, false, true, 79), "html", null, true);
            yield ">
    <div ";
            // line 80
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["title_attributes"] ?? null), "addClass", [($context["field_title_classes"] ?? null)], "method", false, false, true, 80), "html", null, true);
            yield ">";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["label"] ?? null), "html", null, true);
            yield "</div>
    ";
            // line 81
            if ((($tmp = ($context["multiple"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 82
                yield "      <div class=\"field__items\">
    ";
            }
            // line 84
            yield "
    ";
            // line 85
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["items"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                // line 86
                yield "      <div ";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["item"], "attributes", [], "any", false, false, true, 86), "addClass", [($context["field_item_classes"] ?? null)], "method", false, false, true, 86), "html", null, true);
                yield ">";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, $context["item"], "content", [], "any", false, false, true, 86), "html", null, true);
                yield "</div>
    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['item'], $context['_parent']);
            $context = array_intersect_key($context, $_parent);
            $context += $_parent;
            // line 88
            yield "
    ";
            // line 89
            if ((($tmp = ($context["multiple"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 90
                yield "      </div>
    ";
            }
            // line 92
            yield "  </div>
";
        }
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["field_name", "field_type", "label_display", "multiple", "field_utility_classes", "field_item_utility_classes", "field_title_utility_classes", "label_hidden", "attributes", "items", "title_attributes", "label"]);        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "radix:field";
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
        return array (  168 => 92,  164 => 90,  162 => 89,  159 => 88,  147 => 86,  143 => 85,  140 => 84,  136 => 82,  134 => 81,  128 => 80,  123 => 79,  119 => 77,  107 => 75,  102 => 74,  98 => 72,  86 => 70,  82 => 69,  77 => 68,  74 => 67,  72 => 66,  69 => 65,  67 => 63,  66 => 62,  65 => 60,  62 => 58,  60 => 56,  59 => 54,  56 => 52,  54 => 50,  53 => 49,  52 => 48,  51 => 47,  50 => 46,  49 => 45,  48 => 43,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "radix:field", "themes/contrib/radix/components/field/field.twig");
    }
    
    public function ensureSecurityChecked(): void
    {
        if ($this->sandbox->isSandboxed($this->source)) {
            $this->checkSecurity();
        }
    }
    
    public function checkSecurity()
    {
        static $tags = ["set" => 43, "if" => 66, "for" => 69];
        static $filters = ["merge" => 50, "clean_class" => 45, "escape" => 68];
        static $functions = [];
        static $tests = [];

        try {
            $this->sandbox->checkSecurity(
                [0 => "set", 1 => "if", 2 => "for"],
                [0 => "merge", 1 => "clean_class", 2 => "escape"],
                [],
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
