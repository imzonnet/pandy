<?php

namespace App\Http\Controllers;

use App\Http\Requests\BankRequest;
use App\Repositories\BankRepository;
use App\Http\Requests;

class BankController extends Controller
{

    protected $bank;

    public function __construct(BankRepository $bank) {
        $this->bank = $bank;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        $banks = $this->bank->all();
        $title = "List Banks";
        return view('banks.index', compact('banks', 'title'));
    }

    /**
     *
     */
    public function create() {
        $title = "Create new a bank";
        return view('banks.create_edit', compact('title'));
    }

    public function store(BankRequest $request) {
        $this->bank->create($request->all());
        return redirect()->route('bank.index')->with('success_message', 'The bank has been created');
    }

    public function edit($id) {
        $bank = $this->bank->find($id);
        $title = "Edit bank: " . $bank->name;
        return view('banks.create_edit', compact('bank', 'title'));
    }

    public function update($id, BankRequest $request) {
        $bank = $this->bank->find($id);
        $this->bank->update($bank, $request->all());
        return redirect(route('bank.index', $id))->with('success_message', 'The bank has been updated');
    }

    public function destroy($id) {
        $this->bank->delete($id);
        return redirect()->back()->with('success_message', 'The bank has been deleted');
    }

}
