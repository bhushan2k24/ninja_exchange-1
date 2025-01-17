@extends('layouts/contentLayoutMaster')

@section('title', 'Billing & Plans')

@section('vendor-style')
  <!-- vendor css files -->
  <link rel='stylesheet' href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
  <link rel='stylesheet' href="{{ asset(mix('vendors/css/animate/animate.min.css')) }}">
  <link rel='stylesheet' href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">
  <link rel='stylesheet' href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
  <link rel='stylesheet' href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
  <link rel='stylesheet' href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap5.min.css')) }}">
  <link rel='stylesheet' href="{{ asset(mix('vendors/css/tables/datatable/rowGroup.bootstrap5.min.css')) }}">
@endsection
@section('page-style')
  <!-- Page css files -->
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-sweet-alerts.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    @include('content.settings.settings-tabs-btns')
    <!-- billing and plans  -->

    <!-- current plan -->
    <div class="card">
      <div class="card-header border-bottom">
        <h4 class="card-title">Current plan</h4>
      </div>
      <div class="card-body my-2 py-25">
        <div class="row">
          <div class="col-md-6">
            <div class="mb-2 pb-50">
              <h5>Your Current Plan is <strong>Basic</strong></h5>
              <span>A simple start for everyone</span>
            </div>
            <div class="mb-2 pb-50">
              <h5>Active until Dec 09, 2021</h5>
              <span>We will send you a notification upon Subscription expiration</span>
            </div>
            <div class="mb-1">
              <h5>$199 Per Month <span class="badge badge-light-primary ms-50">Popular</span></h5>
              <span>Standard plan for small to medium businesses</span>
            </div>
          </div>
          <div class="col-md-6">
            <div class="alert alert-warning mb-2" role="alert">
              <h6 class="alert-heading">We need your attention!</h6>
              <div class="alert-body fw-normal">your plan requires update</div>
            </div>
            <div class="plan-statistics pt-1">
              <div class="d-flex justify-content-between">
                <h5 class="fw-bolder">Days</h5>
                <h5 class="fw-bolder">4 of 30 Days</h5>
              </div>
              <div class="progress">
                <div
                  class="progress-bar w-75"
                  role="progressbar"
                  aria-valuenow="75"
                  aria-valuemin="0"
                  aria-valuemax="100"
                ></div>
              </div>
              <p class="mt-50">4 days remaining until your plan requires update</p>
            </div>
          </div>
          <div class="col-12">
            <button class="btn btn-primary me-1 mt-1" data-bs-toggle="modal" data-bs-target="#pricingModal">
              Upgrade Plan
            </button>
            <button class="btn btn-outline-danger cancel-subscription mt-1">Cancel Subscription</button>
          </div>
        </div>
      </div>
    </div>
    <!-- / current plan -->

    <!-- payment methods -->
    <div class="card">
      <div class="card-header border-bottom">
        <h4 class="card-title">Payment Methods</h4>
      </div>
      <div class="card-body my-1 py-25">
        <div class="row gx-4">
          <div class="col-lg-6">
            <form id="creditCardForm" class="row gx-2 gy-1 validate-form" onsubmit="return false">
              <div class="col-12">
                <div class="form-check form-check-inline mb-1">
                  <input
                    name="collapsible-payment"
                    class="form-check-input"
                    type="radio"
                    value=""
                    id="collapsible-payment-cc"
                    checked=""
                  />
                  <label class="form-check-label" for="collapsible-payment-cc">Credit/Debit/ATM Card</label>
                </div>
                <div class="form-check form-check-inline mb-1">
                  <input
                    name="collapsible-payment"
                    class="form-check-input"
                    type="radio"
                    value=""
                    id="collapsible-payment-cash"
                  />
                  <label class="form-check-label" for="collapsible-payment-cash">PayPal account</label>
                </div>
              </div>
              <div class="col-12 mt-0">
                <label class="form-label" for="addCardNumber">Card Number</label>
                <div class="input-group input-group-merge">
                  <input
                    id="addCardNumber"
                    name="addCard"
                    class="form-control add-credit-card-mask"
                    type="text"
                    placeholder="5637 8172 1290 7898"
                    aria-describedby="addCard2"
                    data-msg="Please enter your credit card number"
                  />
                  <span class="input-group-text cursor-pointer p-25" id="addCard2">
                    <span class="add-card-type"></span>
                  </span>
                </div>
              </div>

              <div class="col-md-6">
                <label class="form-label" for="addCardName">Name On Card</label>
                <input type="text" id="addCardName" class="form-control" placeholder="John Doe" />
              </div>

              <div class="col-6 col-md-3">
                <label class="form-label" for="addCardExpiryDate">Exp. Date</label>
                <input
                  type="text"
                  id="addCardExpiryDate"
                  class="form-control add-expiry-date-mask"
                  placeholder="MM/YY"
                />
              </div>

              <div class="col-6 col-md-3">
                <label class="form-label" for="addCardCvv">CVV</label>
                <input
                  type="text"
                  id="addCardCvv"
                  class="form-control add-cvv-code-mask"
                  maxlength="3"
                  placeholder="cvv"
                />
              </div>

              <div class="col-12">
                <div class="d-flex align-items-center">
                  <div class="form-check form-switch form-check-primary me-25">
                    <input type="checkbox" class="form-check-input" id="addSaveCard" checked />
                    <label class="form-check-label" for="addSaveCard">
                      <span class="switch-icon-left"><i data-feather="check"></i></span>
                      <span class="switch-icon-right"><i data-feather="x"></i></span>
                    </label>
                  </div>
                  <label class="form-check-label fw-bolder" for="addSaveCard"> Save Card for future billing? </label>
                </div>
              </div>
              <div class="col-12 mt-2 pt-1">
                <button type="submit" class="btn btn-primary me-1">Save Changes</button>
                <button type="reset" class="btn btn-outline-secondary">Discard</button>
              </div>
              <div></div>
              <input type="hidden" />
            </form>
          </div>
          <div class="col-lg-6 mt-2 mt-lg-0">
            <h6 class="fw-bolder mb-2">My Cards</h6>
            <div class="added-cards">
              <div class="cardMaster rounded border p-2 mb-1">
                <div class="d-flex justify-content-between flex-sm-row flex-column">
                  <div class="card-information">
                    <img
                      class="mb-1 img-fluid"
                      src="{{asset('images/icons/payments/mastercard.png')}}"
                      alt="Master Card"
                    />
                    <div class="d-flex align-items-center mb-50">
                      <h6 class="mb-0">Tom McBride</h6>
                      <span class="badge badge-light-primary ms-50">Primary</span>
                    </div>
                    <span class="card-number">∗∗∗∗ ∗∗∗∗ 9856</span>
                  </div>
                  <div class="d-flex flex-column text-start text-lg-end">
                    <div class="d-flex order-sm-0 order-1 mt-1 mt-sm-0">
                      <button class="btn btn-outline-primary me-75" data-bs-toggle="modal" data-bs-target="#editCard">
                        Edit
                      </button>
                      <button class="btn btn-outline-secondary">Delete</button>
                    </div>
                    <span class="mt-2">Card expires at 12/24</span>
                  </div>
                </div>
              </div>
              <div class="cardMaster border rounded p-2">
                <div class="d-flex justify-content-between flex-sm-row flex-column">
                  <div class="card-information">
                    <img
                      class="mb-1 img-fluid"
                      src="{{asset('images/icons/payments/visa.png')}}"
                      alt="Visa Card"
                    />
                    <h6>Mildred Wagner</h6>
                    <span class="card-number">∗∗∗∗ ∗∗∗∗ 5896</span>
                  </div>
                  <div class="d-flex flex-column text-start text-lg-end">
                    <div class="d-flex order-sm-0 order-1 mt-1 mt-sm-0">
                      <button class="btn btn-outline-primary me-75" data-bs-toggle="modal" data-bs-target="#editCard">
                        Edit
                      </button>
                      <button class="btn btn-outline-secondary">Delete</button>
                    </div>
                    <span class="mt-2">Card expires at 02/24</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- / payment methods -->

    <!-- billing address -->
    <div class="card">
      <div class="card-header border-bottom">
        <h4 class="card-title">Billing Address</h4>
      </div>
      <div class="card-body my-2 py-50">
        <form id="formAccountSettings" class="validate-form" onsubmit="return false">
          <div class="row">
            <div class="mb-1 col-md-6">
              <label for="companyName" class="form-label">Company Name</label>
              <input
                type="text"
                id="companyName"
                name="companyName"
                class="form-control"
                placeholder="PIXINVENT"
                data-msg="Please enter company name"
              />
            </div>
            <div class="mb-1 col-md-6">
              <label for="billingEmail" class="form-label">Billing Email</label>
              <input
                class="form-control"
                type="text"
                id="billingEmail"
                name="billingEmail"
                placeholder="john.doe@example.com"
                data-msg="Please enter billing email"
              />
            </div>
            <div class="mb-1 col-md-6">
              <label for="taxId" class="form-label">Tax ID</label>
              <input type="text" id="taxId" name="taxId" class="form-control" placeholder="Enter Tax ID" />
            </div>

            <div class="mb-1 col-md-6">
              <label for="vatNumber" class="form-label">VAT Number</label>
              <input class="form-control" type="text" id="vatNumber" name="vatNumber" placeholder="Enter VAT Number" />
            </div>

            <div class="mb-1 col-md-6">
              <label for="mobileNumber" class="form-label">Mobile</label>
              <input
                class="form-control account-number-mask"
                type="text"
                id="mobileNumber"
                name="mobileNumber"
                placeholder="Enter Mobile Number"
              />
            </div>

            <div class="mb-1 col-md-6">
              <label for="country" class="form-label">Country</label>
              <select class="form-select select2" id="country">
                <option selected="">USA</option>
                <option>Canada</option>
                <option>UK</option>
                <option>Germany</option>
                <option>France</option>
              </select>
            </div>

            <div class="mb-1 col-12">
              <label for="billingAddress" class="form-label">Billing Address</label>
              <input
                type="text"
                class="form-control"
                id="billingAddress"
                name="billingAddress"
                placeholder="Billing Address"
              />
            </div>

            <div class="mb-1 col-md-6">
              <label for="state" class="form-label">State</label>
              <input class="form-control" type="text" id="state" name="state" placeholder="Enter State" />
            </div>

            <div class="mb-1 col-md-6">
              <label for="zipCode" class="form-label">Zip Code</label>
              <input
                type="text"
                class="form-control account-zip-code"
                id="zipCode"
                name="zipCode"
                placeholder="Enter Zip Code"
                maxlength="6"
              />
            </div>
          </div>

          <div class="mt-2">
            <button type="submit" class="btn btn-primary me-1">Save changes</button>
            <button type="reset" class="btn btn-outline-secondary">Discard</button>
          </div>
        </form>
      </div>
    </div>
    <!-- / billing address -->

    <!-- billing history table -->
    <div class="card">
      <table class="invoice-list-table table">
        <thead>
          <tr>
            <th></th>
            <th>#</th>
            <th><i data-feather="trending-up"></i></th>
            <th>Total</th>
            <th class="text-truncate">Issued Date</th>
            <th class="text-truncate">Due Date</th>
            <th>Balance</th>
            <th>Invoice Status</th>
            <th class="cell-fit">Actions</th>
          </tr>
        </thead>
      </table>
    </div>
    <!-- / billing history table -->

    <!--/ billing and plans -->
  </div>
</div>

@include('content/_partials/_modals/modal-pricing')
@include('content/_partials/_modals/modal-edit-cc')
@endsection

@section('vendor-script')
  <!-- vendor files -->
  <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/cleave/cleave.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/cleave/addons/cleave-phone.us.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/extensions/moment.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.bootstrap5.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap5.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/jszip.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/pdfmake.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/vfs_fonts.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.html5.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.print.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.rowGroup.min.js')) }}"></script>
@endsection
@section('page-script')
  <!-- Page js files -->
  <script src="{{ asset(mix('js/scripts/pages/page-pricing.js')) }}"></script>
  <script src="{{ asset(mix('js/scripts/pages/modal-add-new-cc.js')) }}"></script>
  <script src="{{ asset(mix('js/scripts/pages/modal-edit-cc.js')) }}"></script>
  <script src="{{ asset(mix('js/scripts/pages/page-account-settings-billing.js')) }}"></script>
@endsection
