<?php

namespace maddoger\user\common\models\search;

use maddoger\user\common\models\User;
use maddoger\user\common\models\UserProfile;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserSearch represents the model behind the search form about `maddoger\admin\models\User`.
 */
class UserSearch extends User
{
    public $name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'role', 'status', 'last_visit_at', 'created_at', 'updated_at'], 'integer'],
            [['username', 'email', 'name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User::find();
        $query->joinWith('profile');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->sort->attributes['name'] = [
            'asc' => [
                UserProfile::tableName().'.[[first_name]]' => SORT_ASC,
                UserProfile::tableName().'.[[last_name]]' => SORT_ASC,
                UserProfile::tableName().'.[[patronymic]]' => SORT_ASC,
            ],
            'desc' => [
                UserProfile::tableName().'.[[first_name]]' => SORT_DESC,
                UserProfile::tableName().'.[[last_name]]' => SORT_DESC,
                UserProfile::tableName().'.[[patronymic]]' => SORT_DESC,
            ],
            'default' => SORT_ASC
        ];
        $dataProvider->sort->defaultOrder = ['username' => SORT_ASC];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            User::tableName().'.[[role]]' => $this->role,
            User::tableName().'.[[status]]' => $this->status,
            User::tableName().'.[[last_visit_at]]' => $this->last_visit_at,
        ]);

        $query->andFilterWhere(['like', User::tableName().'.[[username]]', $this->username])
            ->andFilterWhere(['like', User::tableName().'.[[email]]', $this->email]);

        $query->andFilterWhere(
            ['or',
                ['like', UserProfile::tableName().'.[[first_name]]', $this->name],
                ['like', UserProfile::tableName().'.[[last_name]]', $this->name],
                ['like', UserProfile::tableName().'.[[patronymic]]', $this->name],
            ]
        );

        return $dataProvider;
    }
}
