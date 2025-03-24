<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

return (new Config())
    ->setRiskyAllowed(true)
    ->setRules(
        [
        '@PER-CS2.0' => true,
        '@PER-CS2.0:risky' => true,
        'align_multiline_comment' => ['comment_type' => 'all_multiline'],
        'array_indentation' => true,
        'array_syntax' => true,
        'assign_null_coalescing_to_coalesce_equal' => true,
        'blank_line_before_statement' => [
            'statements' => [
                'break',
                'continue',
                'exit',
                'phpdoc', 'return', 'throw', 'yield', 'yield_from',
            ],
        ],
        'cast_spaces' => ['space' => 'single'],
        'class_attributes_separation' => true,
        'class_reference_name_casing' => true,
        'clean_namespace' => true,
        'combine_consecutive_issets' => true,
        'combine_consecutive_unsets' => true,
        'combine_nested_dirname' => true,
        'concat_space' => ['spacing' => 'one'],
        // 'declare_strict_types' => true,
        'dir_constant' => true,
        'empty_loop_body' => ['style' => 'braces'],
        'empty_loop_condition' => ['style' => 'while'],
        'fopen_flag_order' => true,
        'fully_qualified_strict_types' => ['import_symbols' => true],
        'global_namespace_import' => ['import_constants' => false, 'import_functions' => false],
        'include' => true,
        'increment_style' => ['style' => 'post'],
        'linebreak_after_opening_tag' => true,
        'list_syntax' => ['syntax' => 'short'],
        'logical_operators' => true,
        'long_to_shorthand_operator' => true,
        'magic_constant_casing' => true,
        'magic_method_casing' => true,
        'method_chaining_indentation' => true,
        'modernize_types_casting' => true,
        'multiline_comment_opening_closing' => true,
        'multiline_whitespace_before_semicolons' => ['strategy' => 'no_multi_line'],
        'native_function_casing' => true,
        'native_type_declaration_casing' => true,
        'no_empty_comment' => true,
        'no_empty_phpdoc' => true,
        'no_empty_statement' => true,
        'no_homoglyph_names' => true,
        'no_leading_namespace_whitespace' => true,
        'no_singleline_whitespace_before_semicolons' => true,
        'no_spaces_around_offset' => true,
        'no_superfluous_elseif' => true,
        // 'no_superfluous_phpdoc_tags' => true,
        'no_trailing_comma_in_singleline' => true,
        'no_unneeded_braces' => true,
        'no_unneeded_import_alias' => true,
        'no_unused_imports' => true,
        'no_useless_concat_operator' => true,
        'no_useless_else' => true,
        'no_useless_nullsafe_operator' => true,
        'no_useless_return' => true,
        'no_useless_sprintf' => true,
        'non_printable_character' => true,
        'normalize_index_brace' => true,
        'nullable_type_declaration' => true,
        'operator_linebreak' => ['position' => 'end', 'only_booleans' => true],
        'ordered_interfaces' => true,
        'ordered_traits' => true,
        'ordered_types' => true,
        'php_unit_assert_new_names' => true,
        'php_unit_construct' => true,
        'php_unit_dedicate_assert_internal_type' => true,
        'return_assignment' => true,
        'self_accessor' => true,
        'semicolon_after_instruction' => true,
        'set_type_to_cast' => true,
        'simplified_if_return' => true,
        'simplified_null_return' => true,
        'single_line_comment_spacing' => true,
        'single_quote' => ['strings_containing_single_quote_chars' => true],
        'strict_param' => true,
        'ternary_to_elvis_operator' => true,
        'ternary_to_null_coalescing' => true,
        'trailing_comma_in_multiline' => true,
        'trim_array_spaces' => true,
        'type_declaration_spaces' => true,
        'void_return' => true,
        'yoda_style' => [
            'always_move_variable' => false,
            'equal' => false,
            'identical' => false,
            'less_and_greater' => false,
        ],
        ]
    )->setFinder(
        Finder::create()
            ->in(__DIR__)
            ->exclude(
                [
                    'legacy-app',
                    'database',
                    'app/*/database',
                    'app/*/resources',
                    'app/*/routes',
                    'config',
                    ]
            )
    );
