<?php

namespace App\Filament\Pages;

use BackedEnum;
use UnitEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Components\Callout;
use Filament\Schemas\Components\EmptyState;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Icon;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Components\UnorderedList;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;

class LayoutShowcase extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $navigationLabel = 'Layout showcase';
    protected static string|UnitEnum|null $navigationGroup = 'Demo';
    protected static ?int $navigationSort = 99;

    protected string $view = 'filament.pages.layout-showcase';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'intro_title' => 'Filament 5 schema showcase',
            'intro_subtitle' => 'Visuele demonstratie van layouts, callouts, empty states en prime components.',

            'grid_name' => 'Tom Demo',
            'grid_email' => 'tom@example.com',
            'grid_status' => 'Actief',

            'aside_title' => 'Rate limiting',
            'aside_value' => '100 requests per minuut',

            'tab_title' => 'Dummy titel',
            'tab_excerpt' => 'Dit is een voorbeeld van inhoud in een tab.',
            'tab_notes' => 'Hier kun je extra notities plaatsen.',

            'wizard_order' => 'Bestelling #2026-001',
            'wizard_delivery' => 'Levering binnen 2 werkdagen',
            'wizard_billing' => 'Facturatie via overschrijving',

            'flex_left' => 'Hoofdinhoud links',
            'flex_right' => 'Ondersteunende info rechts.',

            'fieldset_name' => 'Tom Demo',
            'fieldset_email' => 'tom@example.com',
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Intro')
                    ->description('Deze pagina combineert meerdere schemamogelijkheden uit Filament 5 in één visuele showcase.')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'lg' => 2,
                        ])
                            ->schema([
                                TextInput::make('intro_title')
                                    ->label('Titel van de demo'),

                                TextInput::make('intro_subtitle')
                                    ->label('Subtitel van de demo'),
                            ]),

                        Callout::make('Waarom deze pagina bestaat')
                            ->description('Gebruik deze page om cursisten snel te tonen hoe schema-layouts in Filament eruitzien zonder dat je telkens een volledige resource hoeft te bouwen.')
                            ->info()
                            ->footer([
                                Text::make('Tip: vergelijk deze componenten later met echte resource forms.')
                                    ->color('gray'),
                            ]),
                    ])
                    ->columns(1),

                Section::make('1. Layouts: grid, columns, columnSpan en flex')
                    ->description('Responsieve layouts via Grid en Flex.')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                            'xl' => 4,
                        ])
                            ->schema([
                                TextInput::make('grid_name')
                                    ->label('Naam')
                                    ->columnSpan([
                                        'default' => 1,
                                        'xl' => 2,
                                    ]),

                                TextInput::make('grid_email')
                                    ->label('E-mail')
                                    ->columnSpan([
                                        'default' => 1,
                                        'xl' => 2,
                                    ]),

                                TextInput::make('grid_status')
                                    ->label('Status'),

                                Placeholder::make('grid_hint')
                                    ->label('Uitleg')
                                    ->content('Dit blok toont hoe kolommen en columnSpan samenwerken.'),
                            ]),

                        Flex::make([
                            Textarea::make('flex_left')
                                ->label('Linker blok')
                                ->rows(4),

                            Textarea::make('flex_right')
                                ->label('Rechter blok')
                                ->rows(4),
                        ]),
                    ]),

                Section::make('2. Section met aside()')
                    ->aside()
                    ->schema([
                        TextInput::make('aside_title')->label('Instelling'),
                        TextInput::make('aside_value')->label('Waarde'),
                    ])
                    ->columns(2),

                Section::make('3. Fieldset')
                    ->schema([
                        Fieldset::make('Contactgegevens')
                            ->schema([
                                TextInput::make('fieldset_name')->label('Naam'),
                                TextInput::make('fieldset_email')->label('E-mail'),
                            ])
                            ->columns(2),
                    ]),

                Section::make('4. Tabs')
                    ->schema([
                        Tabs::make('Tabs demo')
                            ->id('layout-showcase-tabs')
                            ->activeTab(2)
                            ->persistTab()
                            ->tabs([
                                Tab::make('Content')
                                    ->badge(3)
                                    ->schema([
                                        TextInput::make('tab_title')->label('Titel'),
                                    ]),

                                Tab::make('Samenvatting')
                                    ->badge(1)
                                    ->schema([
                                        Textarea::make('tab_excerpt')->rows(3),
                                    ]),

                                Tab::make('Notities')
                                    ->badge(7)
                                    ->schema([
                                        Textarea::make('tab_notes')->rows(5),
                                    ]),
                            ]),
                    ]),

                Section::make('5. Wizard')
                    ->schema([
                        Wizard::make([
                            Step::make('Order')
                                ->icon(Heroicon::OutlinedRectangleStack)
                                ->schema([
                                    TextInput::make('wizard_order'),
                                ]),

                            Step::make('Delivery')
                                ->icon(Heroicon::OutlinedTruck)
                                ->schema([
                                    TextInput::make('wizard_delivery'),
                                ]),

                            Step::make('Billing')
                                ->icon(Heroicon::OutlinedDocumentText)
                                ->schema([
                                    TextInput::make('wizard_billing'),
                                ]),
                        ])
                            ->startOnStep(1)
                            ->skippable(),
                    ]),

                Section::make('6. Callouts')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'xl' => 2,
                        ])
                            ->schema([
                                Callout::make('Nieuwe versie beschikbaar')->info(),
                                Callout::make('Publicatie geslaagd')->success(),
                                Callout::make('Let op bij verwijderen')->warning(),
                                Callout::make('Upgrade nodig')
                                    ->warning()
                                    ->actions([
                                        Action::make('upgrade')->button(),
                                        Action::make('compare'),
                                    ])
                                    ->footerActionsAlignment(Alignment::End),
                            ]),
                    ]),

                Section::make('7. Empty state')
                    ->schema([
                        EmptyState::make('Nog geen statistieken beschikbaar')
                            ->icon(Heroicon::OutlinedRectangleStack),
                    ]),

                Section::make('8. Prime components')
                    ->schema([
                        Text::make('Prime components helpen je om uitleg te tonen.')
                            ->size(TextSize::Large)
                            ->weight(FontWeight::Bold),

                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])
                            ->schema([
                                Icon::make(Heroicon::OutlinedLightBulb),
                                UnorderedList::make([
                                    'Tekst',
                                    'Iconen',
                                    'Lijsten',
                                ]),
                            ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function getTitle(): string
    {
        return 'Filament layout showcase';
    }
}
