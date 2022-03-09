<?php

namespace aBillander\SepaSpain;

use AbcAeffchen\SepaUtilities\SepaUtilities;
use AbcAeffchen\Sephpa\SephpaDirectDebit;

use App\Traits\ViewFormatterTrait;

// Direct Debit Bank Order or Remittance
class AbiSephpaDirectDebit extends SephpaDirectDebit
{
    /**
     * Returns the prefix of the names of the generated files.
     * @return string The prefix of the names of the generated files.
     */
    protected function getFileName()
    {
        return 'Sephpa.DirectDebit.' . $this->msgId;
    }

}
