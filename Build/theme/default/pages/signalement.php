<?php 

if(Permission::getInstance()->verifPerm('PermsForum', 'moderation', 'seeSignalement'))
{
	$req = $bddConnection->query('SELECT * FROM cmw_forum_report WHERE vu = 0');
	?><header class="heading-pagination">
	<div class="container-fluid">
		<h1 class="text-uppercase wow fadeInRight" style="color:white;">Signalement</h1>
	</div>
</header><section class="layout" id="page">
	<div class="container">
		<h4 class="title">Gestion des signalements</h4>
	<table class="table table-striped">
		<tr>
			<th>Type de report</th>
			<th>Raison</th>
			<th>Reporteur</th>
			<th>Lien</th>
		</tr>
	<?php 
	while($data = $req->fetch(PDO::FETCH_ASSOC))
	{
		?><tr>
			<td><?php if($data['type'] == 0)
			{
				echo 'Topic';
			}
			else
			{
				echo 'Réponse';
			}
			?></td>
			<td><?php echo $data['reason']; ?></td>
			<td><?php echo $data['reporteur']; ?></td>
			<td><?php 
			if($data['type'] == 0)
			{
				?><a href="index.php?action=r_t_vu&id=<?php echo $data['id_topic_answer']; ?>">Voir le topic</a><?php
				
			}
			else
			{
				$req_topic = $bddConnection->prepare('SELECT * FROM cmw_forum_answer WHERE id = :id');
				$req_topic->execute(array(
					'id' => $data['id_topic_answer']
				));
				$id = $req_topic->fetch(PDO::FETCH_ASSOC);
				$req_page = $bddConnection->prepare('SELECT * FROM cmw_forum_answer WHERE id_topic = :id');
				$req_page->execute(array(
					'id' => $id['id_topic']
				));
				$d_page = $req_page->fetchAll();
				foreach($d_page as $key => $value)
				{
					if($d_page[$key]['id'] == $data['id_topic_answer'])
					{
						$ligne = $key;
					}
				}
				$ligne++;
				$tour = 1;
				unset($d);
				unset($page);
				while($d != TRUE)
				{
					$nb = 20 * $tour;
					if($nb >= $ligne)
					{
						$page = $tour;
						$d = TRUE;
					}
					else
					{
						$tour++;
					}
				}
				?><a href="index.php?action=r_a_vu&id_a=<?php echo $data['id_topic_answer']; ?>&id=<?php echo $id['id_topic']; ?>&page_post=<?php echo $page; ?>">Lien vers la réponse</a><?php
			}
			?></td>
		</tr><?php
	}
	?></table>
	</div></section><?php 
}