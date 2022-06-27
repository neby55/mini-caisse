<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use App\Models\Product;
use App\Enums\ProductStatus;
use Illuminate\Validation\Rules\Enum;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Alert;

class ProductEditScreen extends Screen
{
    /**
     * @var Product
     */
    public $product;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Product $product): iterable
    {
        return [
            'product' => $product
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->product->exists ? 'Editer le produit' : 'Créer un produit';
    }

    /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return "Gérer les produits qui pourront être commandés via l'application";
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Retour à la liste')
                ->icon('arrow-left')
                ->route('platform.product.list'),

            Button::make('Supprimer')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->product->exists)
                ->confirm('Êtes-vous certain de vouloir supprimer cette élément ?'),
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::rows([
                Input::make('name')
                    ->title('Nom')
                    ->placeholder('nom du produit')
                    ->help('Rensigner un nom court et compréhensible rapidement')
                    ->required()
                    ->value($this->product->exists ? $this->product->name : ''),

                Input::make('price')
                    ->title('Prix')
                    ->placeholder('prix du produit')
                    ->help('Le montant est exprimé en € (euros), et le séparateur des décimales est le . (point)')
                    ->mask([
                        'alias' => 'currency',
                        'prefix' => '',
                        'groupSeparator' => ' ',
                        'digitsOptional' => true
                    ])
                    ->required()
                    ->value($this->product->exists ? $this->product->price : ''),

                Select::make('status')
                    ->title('Statut')
                    ->options([
                        'created' => 'créé',
                        'enabled' => 'actif',
                        'disabled' => 'désactivé'
                    ])
                    ->required()
                    ->value($this->product->exists ? $this->product->status : ''),
    
                Button::make('Valider')
                    ->icon('note')
                    ->method('createOrUpdate')
                    ->class('btn btn-success btn-block')
                    ->canSee($this->product->exists)
            ])
        ];
    }

    /**
     * @param Product $product
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Product $product, Request $request)
    {
        // Update
        if (!empty($product)) {
            $validated = $request->validate([
                'name' => 'required|max:255',
                'price' => 'required|numeric|gt:0',
                'status' => [new Enum(ProductStatus::class)]
            ]);

            if ($validated) {
                $product->fill($request->all())->save();
                Alert::info('Le produit a bien été modifié');
            } else {
                Alert::warning('Erreur dans la modification du produit');
            }
            return redirect()->route('platform.product.edit', $product);
        } else { // Add
            $validated = $request->validate([
                'name' => 'required|unique:products|max:255',
                'price' => 'required|numeric|gt:0',
                'status' => [new Enum(ProductStatus::class)]
            ]);

            if ($validated) {
                $product->fill($request->all())->save();
                Alert::info('Le produit a bien été créé');
            } else {
                Alert::warning('Erreur dans la création du produit');
            }
            return redirect()->route('platform.product.list');
        }
    }

    /**
     * @param Product $product
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Product $product)
    {
        $product->delete();

        Alert::info('Produit supprimé');

        return redirect()->route('platform.product.list');
    }
}
