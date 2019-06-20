<?php

namespace backend\form;

use Yii;
use yii\db\Expression;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\PostComment;
use common\models\Post;
use common\models\Customer;

class PostCommentSearch extends Model
{
	use Search;

    /**
     * @var integer 评论id
     */
    public $id;

    /**
     * @var string 文章标题
     */
	public $post_title;

    /**
     * @var string 文章 URL
     */
	public $post_url;

    /**
     * @var string 分类名.
     */
	public $category;

	/**
	 * @var string 客户邮件地址
	 */
	public $customer_email;

	/**
	 * @var string 客户nickname
	 */
	public $customer_nickname;


    public $status;


    public $comment;


	public function rules()
	{
		return [
           [
           	   [
           	   	   'id', 
           	   	   'post_title',
           	   	   'post_url', 
           	   	   'customer_email', 
           	   	   'customer_nickname',
           	   	   'status',
           	   	   'comment',
           	   	],
           	   	'safe',
           	],
		];
	}

	public function attributeLabels()
	{
		return [
           'id' => 'ID',
           'post_title'        => Yii::t('all', 'Post title'),
           'post_url'          => Yii::t('all', 'Post url path'),
           'customer_email'    => Yii::t('all', 'Customer email address'),
           'customer_nickname' => Yii::t('all', 'Customer nickname'),
           'status'            => Yii::t('all', 'Status'),
           'comment'           => Yii::t('all', 'Comment'),
		];
	}

	/**
     * 
     * @param  [type] $params [description]
     * @return [type]         [description]
     */
	public function search($params)
	{
        return new ActiveDataProvider([
            'query' => $this->_getSearchQuery($params),
            'sort' => [
                'attributes' => [
                	'id',
                	'comment',
                	'status',
                    'post_title' => [
                        'asc' => ['p.title' => SORT_ASC],
                        'desc' => ['p.title' => SORT_DESC],
                    ],
                    'post_url' => [
                        'asc' => ['p.url_path' => SORT_ASC],
                        'desc' => ['p.url_path' => SORT_DESC],
                    ],
                    'customer_email' => [
                        'asc' => ['c.email' => SORT_ASC],
                        'desc' => ['c.email' => SORT_DESC],
                    ],
                    'customer_nickname' => [
                        'asc' => ['c.nickname' => SORT_ASC],
                        'desc' => ['c.nickname' => SORT_DESC],
                    ],
                    
                ],
            ],
        ]);
	}

	protected function _search($success)
	{
		$query = PostComment::find()->with([
			'customer' => function($query) {
				$query->select(['id', 'nickname', 'email']);
			}, 
			'post' => function($query) {
                $query->select(['id', 'title', 'url_path']);
			},
		]);
		if($success) {
			$query->alias('pc');
			$query->innerJoin(['p' => Post::tableName()], [
				'and',
                ['pc.post_id' => new Expression('`p`.`id`')],
                [
                	'and', 
                	['like', 'p.title', $this->post_title],
                    ['like', 'p.url_path', $this->post_url],
                ],
			])->innerJoin(['c' => Customer::tableName()], [
                'and',
                ['pc.customer_id' => new Expression('`c`.`id`')],
                [
                    'and',
                    ['like', 'c.email', $this->customer_email],
                    ['like', 'c.nickname', $this->customer_nickname],
                ],
			])->andFilterWhere([
				'and',
				['id' => $this->id],
				['status' => $this->status],
				['like', 'comment', $this->comment],
			]);
		}
		return $query;
	}

}