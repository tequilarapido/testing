<?php

namespace Tequilarapido\Testing\Concerns;

trait DatatableAsserts
{
    /**
     * Asserts that the returned json has a correct datatable structure.
     *
     * @return $this
     */
    public function seeValidDatatable()
    {
        $this->seeJsonStructure(['aaData', 'iTotalDisplayRecords', 'iTotalRecords', 'sEcho']);
        
        return $this;
    }

    /**
     * Asserts datatable rows count.
     *
     * @param $count
     *
     * @return $this
     */
    public function seeDatatableCount($count)
    {
        $datatable = $this->decodeResponseJson();
        
        $this->assertEquals($datatable['iTotalDisplayRecords'], $count);
        
        return $this;
    }

    /**
     * Asserts a column content.
     *
     * @param $column
     * @param $expected
     * @return $this
     */
    public function seeInDatatableColumn($column, $expected)
    {
        $datatable = $this->decodeResponseJson();

        $actual = collect($datatable['aaData'])->reduce(function ($carry, $item) use ($column) {
            $carry[] = $item[$column];

            return $carry;
        }, []);

        $this->assertEquals($expected, $actual);

        return $this;
    }
    
     /**
     * Asserts a column content in whatever order.
     *
     * @param $column
     * @param $expected
     * @return $this
     */
    public function seeInWhateverOrderInDatatable($column, $expected)
    {
        $datatable = $this->decodeResponseJson();

        $actual = collect($datatable['aaData'])->reduce(function ($carry, $item) use ($column) {
            $carry[] = $item[$column];

            return $carry;
        }, []);


        $this->assertEquals(0, count(array_diff($expected, $actual)), 'Not the same array');

        return $this;
    }

    /**
     * Asserts first column content.
     *
     * @param $expected
     * @return DatatableAsserts
     */
    public function seeInDatatableFirstColumn($expected)
    {
        return $this->seeInDatatableColumn(0, $expected);
    }

    /**
     * Asserts second column content.
     *
     * @param $expected
     * @return DatatableAsserts
     */
    public function seeInDatatableSecondColumn($expected)
    {
        return $this->seeInDatatableColumn(1, $expected);
    }

    /**
     * Asserts third column content.
     *
     * @param $expected
     * @return DatatableAsserts
     */
    public function seeInDatatableThrirdColumn($expected)
    {
        return $this->seeInDatatableColumn(2, $expected);
    }
}
