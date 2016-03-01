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
     * Show list banks
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        $banks = $this->bank->paginate(10);
        $title = "List Banks";
        return view('banks.index', compact('banks', 'title'));
    }

    /**
     * Create new a bank
     */
    public function create() {
        $title = "Create new a bank";
        return view('banks.create_edit', compact('title'));
    }

    /**
     * Store new record
     *
     * @param BankRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(BankRequest $request) {
        $this->bank->create($request->all());
        return redirect()->route('bank.index')->with('success_message', 'The bank has been created');
    }

    /**
     * Edit the bank info
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id) {
        $bank = $this->bank->find($id);
        $title = "Edit bank: " . $bank->name;
        return view('banks.create_edit', compact('bank', 'title'));
    }

    /**
     * Update the bank info
     *
     * @param $id
     * @param BankRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, BankRequest $request) {
        $bank = $this->bank->find($id);
        $this->bank->update($bank, $request->all());
        return redirect(route('bank.index', $id))->with('success_message', 'The bank has been updated');
    }

    /**
     * Delete a bank
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id) {
        $this->bank->delete($id);
        return redirect()->back()->with('success_message', 'The bank has been deleted');
    }

}
