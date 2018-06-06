<?php

// Enrico Simonetti
// enricosimonetti.com

// 2018-06-06 on 8.0.0 with MySQL

class CustomFilterApi extends FilterApi
{
    public function registerApiRest()
    {
        return parent::registerApiRest();
    }

    protected static function addFilter($field, $filter, SugarQuery_Builder_Where $where, SugarQuery $q)
    {
        if ($field == 'tag' && !empty($filter['$and_in']) && is_array($filter['$and_in'])) {

            // if there is only one tag, use the parent method
            if (count($filter['$and_in']) == 1) {
                $filter['$in'] = $filter['$and_in'];
                unset($filter['$and_in']);
                parent::addFilter($field, $filter, $where, $q);
            } else {

                // exception if more than 5 tags are passed, to hard limit the number of joins
                if (count($filter['$and_in']) > 5) {
                    throw new SugarApiExceptionInvalidParameter('LBL_TOO_MANY_AND_IN_CONDITIONS');
                }

                // if there is more than one tag
                $module_name = $q->getFromBean()->module_name;
                $main_table = $q->getFromAlias();

                $counter = 1;

                // add two inner join per tag
                foreach ($filter['$and_in'] as $tag_name) {
                    $tags_relate_table = 'tag_bean_rel_' . $counter;
                    $tags_table = 'tags_' . $counter;

                    $q->joinTable('tag_bean_rel', array('alias' => $tags_relate_table, 'joinType' => 'INNER'))
                        ->on()
                        ->equalsField($main_table.'.id', $tags_relate_table . '.bean_id')
                        ->equals($tags_relate_table . '.bean_module', $module_name)
                        ->equals($tags_relate_table . '.deleted', 0);

                    $q->joinTable('tags', array('alias' => $tags_table, 'joinType' => 'INNER'))
                        ->on()
                        ->equalsField($tags_relate_table . '.tag_id', $tags_table . '.id')
                        ->equals($tags_table . '.deleted', 0)
                        ->equals($tags_table . '.name_lower', strtolower($tag_name));

                    $counter++;
                }
            }
        } else {
            parent::addFilter($field, $filter, $where, $q);
        }
    }
}
